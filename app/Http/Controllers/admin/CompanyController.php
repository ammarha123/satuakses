<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::with('user')->latest()->paginate(15);
        return view('admin.company.index', compact('companies'));
    }

    public function create()
    {
        return view('admin.company.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email|unique:companies,email',
            'phone'       => 'nullable|string|max:50',
            'website'     => 'nullable|url',
            'province'    => 'nullable|string|max:100',
            'city'        => 'nullable|string|max:100',
            'address'     => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'nullable|in:active,pending,suspended',
        ]);

        $plainPassword = '123456789';

        $company = DB::transaction(function () use ($data, $plainPassword) {

            $user = User::create([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'password'          => Hash::make($plainPassword),
                'email_verified_at' => now(), // optional
            ]);

            if (method_exists($user, 'assignRole')) {
                $user->assignRole('employer');
            }

            $slug = Str::slug($data['name']);
            $base = $slug;
            $i = 1;
            while (Company::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i++;
            }

            return Company::create([
                'user_id'    => $user->id,
                'name'       => $data['name'],
                'slug'       => $slug,
                'email'      => $data['email'],
                'phone'      => $data['phone'] ?? null,
                'website'    => $data['website'] ?? null,
                'province'   => $data['province'] ?? null,
                'city'       => $data['city'] ?? null,
                'address'    => $data['address'] ?? null,
                'description' => $data['description'] ?? null,
                'status'     => $data['status'] ?? 'active',
            ]);
        });

        return redirect()->route('admin.company.create_success', [
            'company' => $company->id,
            'password' => $plainPassword
        ]);
    }
    public function createSuccess($companyId, Request $request)
    {
        $company = Company::with('user')->findOrFail($companyId);
        $password = $request->password;

        return view('admin.company.create_success', compact('company', 'password'));
    }
}
