<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cliente;
use App\Models\Articulo;
use App\Models\Reparacion;
use App\Models\Tecnico;
use App\Models\Repuesto;
use App\Models\Pago;
use App\Models\HistorialReparacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $totalClientes = Cliente::count();
        $totalArticulos = Articulo::count();
        $totalTecnicos = Tecnico::count();
        $totalReparaciones = Reparacion::count();
        $totalRepuestos = Repuesto::count();
        $ingresosTotales = Pago::sum('monto');
        
        // Reparaciones por estado
        $reparacionesPorEstado = Reparacion::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get();
        
        // Últimas reparaciones
        $ultimasReparaciones = Reparacion::with(['articulo.cliente', 'tecnico'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Top técnicos
        $topTecnicos = Tecnico::withCount('reparaciones')
            ->orderBy('reparaciones_count', 'desc')
            ->limit(5)
            ->get();
        
        // Ingresos por mes
        $ingresosPorMes = Pago::select(
                DB::raw('MONTH(created_at) as mes'),
                DB::raw('YEAR(created_at) as año'),
                DB::raw('SUM(monto) as total')
            )
            ->groupBy('año', 'mes')
            ->orderBy('año', 'desc')
            ->orderBy('mes', 'desc')
            ->limit(6)
            ->get();
        
        // Usuarios del sistema
        $users = User::all();
        
        return view('admin.dashboard', compact(
            'totalClientes',
            'totalArticulos',
            'totalTecnicos',
            'totalReparaciones',
            'totalRepuestos',
            'ingresosTotales',
            'reparacionesPorEstado',
            'ultimasReparaciones',
            'topTecnicos',
            'ingresosPorMes',
            'users'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'user',
            'is_active' => $request->has('is_active')
        ]);

        return back()->with('success', 'Usuario registrado correctamente.');
    }

    public function toggle(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();
        return back()->with('success', 'Estado del usuario actualizado.');
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,user'
        ]);
        
        $user->role = $request->role;
        $user->save();
        
        return back()->with('success', 'Rol de usuario actualizado.');
    }

    public function reportesGenerales()
    {
        $reparacionesPorMes = Reparacion::select(
                DB::raw('MONTH(created_at) as mes'),
                DB::raw('YEAR(created_at) as año'),
                DB::raw('count(*) as total')
            )
            ->groupBy('año', 'mes')
            ->orderBy('año', 'desc')
            ->orderBy('mes', 'desc')
            ->get();
        
        $ingresosPorMes = Pago::select(
                DB::raw('MONTH(created_at) as mes'),
                DB::raw('YEAR(created_at) as año'),
                DB::raw('SUM(monto) as total')
            )
            ->groupBy('año', 'mes')
            ->orderBy('año', 'desc')
            ->orderBy('mes', 'desc')
            ->get();
        
        $topArticulos = Articulo::withCount('reparaciones')
            ->orderBy('reparaciones_count', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.reportes', compact('reparacionesPorMes', 'ingresosPorMes', 'topArticulos'));
    }
    
    public function auditoria()
    {
        $historial = HistorialReparacion::with(['tecnico', 'reparacion.articulo.cliente'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        
        return view('admin.auditoria', compact('historial'));
    }
    
    public function respaldos()
    {
        $backups = [];
        $backupPath = storage_path('app/backups');
        
        if (is_dir($backupPath)) {
            $files = scandir($backupPath);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..' && pathinfo($file, PATHINFO_EXTENSION) == 'sql') {
                    $backups[] = [
                        'name' => $file,
                        'size' => filesize($backupPath . '/' . $file),
                        'date' => date('d/m/Y H:i:s', filemtime($backupPath . '/' . $file))
                    ];
                }
            }
            rsort($backups);
        }
        
        return view('admin.respaldos', compact('backups'));
    }
    
    public function configuracion()
    {
        // Cargar configuraciones existentes
        $configTaller = Storage::exists('config_taller.json') ? json_decode(Storage::get('config_taller.json'), true) : [];
        $configFacturacion = Storage::exists('config_facturacion.json') ? json_decode(Storage::get('config_facturacion.json'), true) : [];
        $configNotificaciones = Storage::exists('config_notificaciones.json') ? json_decode(Storage::get('config_notificaciones.json'), true) : [];
        $configSeguridad = Storage::exists('config_seguridad.json') ? json_decode(Storage::get('config_seguridad.json'), true) : [];
        
        return view('admin.configuracion', compact('configTaller', 'configFacturacion', 'configNotificaciones', 'configSeguridad'));
    }
    
    // ==================== MÉTODOS PARA RESPALDOS ====================
    
    public function generarBackup(Request $request)
    {
        $tipo = $request->tipo ?? 'completo';
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '_' . $tipo . '.sql';
        $backupPath = storage_path('app/backups');
        
        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0777, true);
        }
        
        $fullPath = $backupPath . '/' . $filename;
        
        // 👈 RUTA COMPLETA DE MYSQLDUMP
        $mysqlPath = 'D:\xampp\mysql\bin\mysqldump.exe';
        
        // Credenciales desde .env
        $user = env('DB_USERNAME', 'root');
        $password = env('DB_PASSWORD', '');
        $host = env('DB_HOST', '127.0.0.1');
        $database = env('DB_DATABASE', 'electrobol');
        
        // Construir comando usando la ruta completa
        if ($tipo == 'estructura') {
            $command = sprintf(
                '"%s" --user=%s --password=%s --host=%s --no-data %s > "%s"',
                $mysqlPath,
                $user,
                $password,
                $host,
                $database,
                $fullPath
            );
        } elseif ($tipo == 'datos') {
            $command = sprintf(
                '"%s" --user=%s --password=%s --host=%s --no-create-info %s > "%s"',
                $mysqlPath,
                $user,
                $password,
                $host,
                $database,
                $fullPath
            );
        } else {
            $command = sprintf(
                '"%s" --user=%s --password=%s --host=%s %s > "%s"',
                $mysqlPath,
                $user,
                $password,
                $host,
                $database,
                $fullPath
            );
        }
        
        exec($command, $output, $returnCode);
        
        if ($returnCode === 0 && file_exists($fullPath) && filesize($fullPath) > 0) {
            return back()->with('success', 'Respaldo generado correctamente: ' . $filename);
        } else {
            $errorMsg = "Código: $returnCode\n";
            $errorMsg .= "Salida: " . implode("\n", $output);
            return back()->with('error', 'Error al generar el respaldo. ' . $errorMsg);
        }
    }
    
    public function descargarBackup($file)
    {
        $path = storage_path('app/backups/' . $file);
        
        if (file_exists($path)) {
            return response()->download($path, $file, [
                'Content-Type' => 'application/sql',
                'Content-Disposition' => 'attachment; filename="' . $file . '"'
            ]);
        } else {
            return back()->with('error', 'El archivo no existe.');
        }
    }
    
    public function restaurarBackup($file)
    {
        $path = storage_path('app/backups/' . $file);
        
        if (file_exists($path)) {
            $user = env('DB_USERNAME', 'root');
            $password = env('DB_PASSWORD', '');
            $host = env('DB_HOST', '127.0.0.1');
            $database = env('DB_DATABASE', 'electrobol');
            
            $mysqlPath = 'D:\xampp\mysql\bin\mysql.exe';
            
            $command = sprintf(
                '"%s" --user=%s --password=%s --host=%s %s < "%s"',
                $mysqlPath,
                $user,
                $password,
                $host,
                $database,
                $path
            );
            
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0) {
                return redirect()->route('admin.dashboard')->with('success', 'Base de datos restaurada correctamente.');
            } else {
                return back()->with('error', 'Error al restaurar el respaldo.');
            }
        } else {
            return back()->with('error', 'El archivo no existe.');
        }
    }
    
    public function eliminarBackup($file)
    {
        $path = storage_path('app/backups/' . $file);
        
        if (file_exists($path)) {
            unlink($path);
            return back()->with('success', 'Respaldo eliminado correctamente.');
        } else {
            return back()->with('error', 'El archivo no existe.');
        }
    }
    
    public function limpiarBackups()
    {
        $backupPath = storage_path('app/backups');
        $files = glob($backupPath . '/*.sql');
        $now = time();
        $days30 = 30 * 24 * 60 * 60;
        $deleted = 0;
        
        foreach ($files as $file) {
            if (filemtime($file) < ($now - $days30)) {
                unlink($file);
                $deleted++;
            }
        }
        
        return back()->with('success', "Se eliminaron {$deleted} respaldos antiguos (más de 30 días).");
    }
    
    public function programarBackup(Request $request)
    {
        $schedule = [
            'frecuencia' => $request->frecuencia,
            'hora' => $request->hora,
            'dia_semana' => $request->dia_semana,
            'dia_mes' => $request->dia_mes,
            'activo' => true,
            'ultima_actualizacion' => now()->toDateTimeString()
        ];
        
        Storage::put('backup_schedule.json', json_encode($schedule, JSON_PRETTY_PRINT));
        
        return back()->with('success', 'Respaldo programado correctamente.');
    }
    
    public function configurarBackup(Request $request)
    {
        $config = [
            'ubicacion' => $request->ubicacion,
            'max_backups' => $request->max_backups,
            'notificar_email' => $request->has('notificar_email'),
            'ultima_actualizacion' => now()->toDateTimeString()
        ];
        
        Storage::put('backup_config.json', json_encode($config, JSON_PRETTY_PRINT));
        
        return back()->with('success', 'Configuración guardada correctamente.');
    }
    
    // ==================== MÉTODOS DE CONFIGURACIÓN ====================
    
    public function updateConfiguracion(Request $request)
    {
        $config = [
            'nombre_taller' => $request->nombre_taller,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'nit' => $request->nit,
            'email' => $request->email,
            'ultima_actualizacion' => now()->toDateTimeString()
        ];
        
        Storage::put('config_taller.json', json_encode($config, JSON_PRETTY_PRINT));
        
        return back()->with('success', 'Configuración del taller actualizada.');
    }
    
    public function updateFacturacion(Request $request)
    {
        $config = [
            'iva' => $request->iva,
            'costo_evaluacion' => $request->costo_evaluacion,
            'factura_auto' => $request->has('factura_auto'),
            'incluir_iva' => $request->has('incluir_iva'),
            'ultima_actualizacion' => now()->toDateTimeString()
        ];
        
        Storage::put('config_facturacion.json', json_encode($config, JSON_PRETTY_PRINT));
        
        return back()->with('success', 'Configuración de facturación actualizada.');
    }
    
    public function updateNotificaciones(Request $request)
    {
        $config = [
            'notif_cliente' => $request->has('notif_cliente'),
            'notif_admin' => $request->has('notif_admin'),
            'email_notificaciones' => $request->email_notificaciones,
            'ultima_actualizacion' => now()->toDateTimeString()
        ];
        
        Storage::put('config_notificaciones.json', json_encode($config, JSON_PRETTY_PRINT));
        
        return back()->with('success', 'Configuración de notificaciones actualizada.');
    }
    
    public function updateSeguridad(Request $request)
    {
        $config = [
            'session_timeout' => $request->session_timeout,
            'two_factor' => $request->has('2fa'),
            'bloquear_intentos' => $request->has('intentos'),
            'ultima_actualizacion' => now()->toDateTimeString()
        ];
        
        Storage::put('config_seguridad.json', json_encode($config, JSON_PRETTY_PRINT));
        
        return back()->with('success', 'Configuración de seguridad actualizada.');
    }
}