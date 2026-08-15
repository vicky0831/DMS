<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'company_name' => ['required', 'string', 'max:255'],
            'company_registration_number' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $company = Company::create([
            'name' => $validated['company_name'],
            'slug' => $this->uniqueCompanySlug($validated['company_name']),
            'registration_number' => $validated['company_registration_number'] ?? null,
            'timezone' => 'Asia/Kuala_Lumpur',
            'locale' => 'en_MY',
            'subscription_status' => 'trial',
        ]);

        foreach ($this->sme_default_categories() as $index => $category) {
            \App\Models\Category::create([
                'company_id' => $company->id,
                'name' => $category['name'],
                'group_name' => $category['group_name'],
                'is_default' => true,
                'sort_order' => $index + 1,
            ]);
        }

        $company->users()->attach($user->id, [
            'role' => 'owner',
            'permissions' => json_encode(['*']),
            'status' => 'active',
            'is_primary' => true,
        ]);

        Auth::login($user, true);

        $request->session()->regenerate();

        return response()->json([
            'message' => 'Account created successfully.',
            'data' => $this->mePayload($user, $company),
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, true)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        $user = $request->user();
        $company = $user?->companies()->first();

        return response()->json([
            'message' => 'Logged in successfully.',
            'data' => $this->mePayload($user, $company),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        $company = $user?->companies()->first();

        return response()->json([
            'data' => $this->mePayload($user, $company),
        ]);
    }

    private function mePayload(?User $user, ?Company $company): array
    {
        return [
            'user' => $user?->only(['id', 'name', 'email']),
            'company' => $company?->only(['id', 'name', 'slug', 'timezone', 'locale']),
        ];
    }

    private function uniqueCompanySlug(string $companyName): string
    {
        $baseSlug = Str::slug($companyName) ?: 'company';
        $slug = $baseSlug;
        $suffix = 1;

        while (Company::where('slug', $slug)->exists()) {
            $suffix++;
            $slug = $baseSlug.'-'.$suffix;
        }

        return $slug;
    }

    private function sme_default_categories(): array
    {
        return [
            ['name' => 'SSM', 'group_name' => 'Company'],
            ['name' => 'Business Licence', 'group_name' => 'Company'],
            ['name' => 'Local Authority Licence', 'group_name' => 'Company'],
            ['name' => 'Insurance', 'group_name' => 'Company'],
            ['name' => 'Tenancy Agreement', 'group_name' => 'Company'],
            ['name' => 'Company Contract', 'group_name' => 'Company'],
            ['name' => 'Passport', 'group_name' => 'Employee'],
            ['name' => 'Work Permit', 'group_name' => 'Employee'],
            ['name' => 'Employment Contract', 'group_name' => 'Employee'],
            ['name' => 'Road Tax', 'group_name' => 'Vehicle'],
            ['name' => 'Motor Insurance', 'group_name' => 'Vehicle'],
            ['name' => 'Premise Licence', 'group_name' => 'Premises'],
            ['name' => 'Fire/Safety Certificate', 'group_name' => 'Premises'],
            ['name' => 'Domain', 'group_name' => 'IT'],
            ['name' => 'Hosting', 'group_name' => 'IT'],
            ['name' => 'Software Subscription', 'group_name' => 'IT'],
            ['name' => 'Supplier Contract', 'group_name' => 'Supplier/Contract'],
            ['name' => 'Service Agreement', 'group_name' => 'Supplier/Contract'],
        ];
    }
}
