<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Memberikan izin penuh kepada admin untuk semua aksi.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Hanya admin yang bisa melihat daftar semua user (diizinkan oleh before())
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Admin bisa lihat semua (diizinkan oleh before())
        // User biasa hanya bisa lihat profilnya sendiri.
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     * PENTING: Untuk registrasi publik, kita tidak bisa mengandalkan $user yang login.
     * Metode ini harus mengizinkan siapa saja untuk mencoba membuat akun.
     * Middleware 'auth' pada route akan melindungi halaman create user di admin panel.
     */
    public function create(User $user): bool
    {
        // Hanya admin yang bisa membuat user dari panel admin (diizinkan oleh before())
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Admin bisa update semua (diizinkan oleh before())
        // User biasa hanya bisa update profilnya sendiri.
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Admin bisa hapus (diizinkan oleh before())
        // User tidak boleh menghapus user lain
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        // Hanya untuk admin (diizinkan oleh before())
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        // Hanya untuk admin (diizinkan oleh before())
        return false;
    }
}