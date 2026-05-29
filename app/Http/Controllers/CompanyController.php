<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class CompanyController extends Controller
{
    /**
     * Display the company management page.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $company = $user->company;
        $members = $company ? $company->users()->orderBy('created_at', 'desc')->get() : collect();

        return view('company.index', [
            'user' => $user,
            'company' => $company,
            'members' => $members,
        ]);
    }

    /**
     * Update the company information.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
        ]);

        $company = $request->user()->company;
        if ($company) {
            $company->update($request->only(['name', 'email', 'phone', 'address']));
        }

        return Redirect::route('company.index')->with('success', 'Informasi perusahaan berhasil diperbarui.');
    }

    /**
     * Add a new team member to the company.
     */
    public function addMember(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $company = $request->user()->company;
        if (!$company) {
            return back()->with('error', 'Perusahaan tidak ditemukan.');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company_id' => $company->id,
        ]);

        return Redirect::route('company.index')->with('success', 'Anggota tim berhasil ditambahkan.');
    }

    /**
     * Remove a team member from the company.
     */
    public function removeMember(Request $request, User $member): RedirectResponse
    {
        $user = $request->user();
        $company = $user->company;

        // Cannot remove yourself
        if ($member->id === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri dari sini.');
        }

        // Only remove members of the same company
        if ($member->company_id !== $company->id) {
            return back()->with('error', 'Anggota tidak ditemukan di perusahaan ini.');
        }

        User::destroy($member->id);

        return Redirect::route('company.index')->with('success', 'Anggota tim berhasil dihapus.');
    }
}
