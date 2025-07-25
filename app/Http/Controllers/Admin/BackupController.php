<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Backup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\DB;

class BackupController extends Controller
{
    private $backupDisk = 'local'; // Gunakan disk 'local'
    private $backupPath = 'backups'; // Simpan di storage/app/backups

    /**
     * Terapkan middleware untuk memastikan hanya Admin yang bisa mengakses.
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('role:admin');
    // }

    /**
     * Menampilkan halaman utama dan daftar backup yang ada.
     */
    public function index()
    {
        // Ambil semua data backup dari database, urutkan dari yang terbaru
        $backups = Backup::with('creator')->latest()->get();
        return view('admin.backup.index', compact('backups'));
    }

    /**
     * Membuat file backup baru dan menyimpannya di database.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $fileName = 'backup-' . date('Y-m-d_H-i-s') . '.sql';
            $filePath = $this->backupPath . '/' . $fileName;

            // Pastikan direktori ada
            Storage::disk($this->backupDisk)->makeDirectory($this->backupPath);
            $fullPath = Storage::disk($this->backupDisk)->path($filePath);

            // Kredensial database
            $dbHost = config('database.connections.mysql.host');
            $dbPort = config('database.connections.mysql.port');
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPassword = config('database.connections.mysql.password');

            // Bangun perintah mysqldump
            $command = sprintf(
                'mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s',
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbUser),
                escapeshellarg($dbPassword),
                escapeshellarg($dbName),
                escapeshellarg($fullPath)
            );

            // Jalankan perintah
            $process = Process::fromShellCommandline($command);
            $process->setTimeout(3600); // Timeout 1 jam untuk database besar
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            // Simpan record ke database
            Backup::create([
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_size' => Storage::disk($this->backupDisk)->size($filePath),
                'created_by_user_id' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('admin.backup.index')->with('success', 'Backup database berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Database backup failed: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Gagal membuat backup database. Silakan cek log untuk detail.');
        }
    }

    /**
     * Mengunduh file backup yang sudah ada.
     */
    public function download(Backup $backup)
    {
        if (Storage::disk($this->backupDisk)->exists($backup->file_path)) {
            return Storage::disk($this->backupDisk)->download($backup->file_path, $backup->file_name);
        }

        return redirect()->back()->with('error', 'File backup tidak ditemukan.');
    }

    /**
     * Menghapus file backup dan record dari database.
     */
    public function destroy(Backup $backup)
    {
        DB::beginTransaction();
        try {
            // Hapus file dari storage
            if (Storage::disk($this->backupDisk)->exists($backup->file_path)) {
                Storage::disk($this->backupDisk)->delete($backup->file_path);
            }

            // Hapus record dari database
            $backup->delete();

            DB::commit();

            return redirect()->route('admin.backup.index')->with('success', 'Backup berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus backup: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus backup.');
        }
    }
}
