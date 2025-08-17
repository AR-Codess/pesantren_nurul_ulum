<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassLevel;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    /**
     * Mengambil data santri berdasarkan banyak Jenjang Kelas untuk request AJAX.
     */
    public function getSantriByClassMulti(Request $request)
    {
        $ids = $request->input('class_level_ids');
        $idArray = array_filter(explode(',', $ids));
        $santri = \App\Models\User::role('user')
            ->whereIn('class_level_id', $idArray)
            ->orderBy('nama_santri')
            ->get(['id', 'nis', 'nama_santri']);
        return response()->json($santri);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // Set default to 4 records per page
        $classLevelFilter = $request->input('class_level_id');
        $guruFilter = $request->input('guru_id');
        $mataPelajaranFilter = $request->input('mata_pelajaran');

        $kelas = Kelas::with(['guru', 'classLevels'])
            ->withCount('users')
            ->when($search, function ($query, $search) {
                return $query->where('mata_pelajaran', 'like', '%' . $search . '%')
                    ->orWhere('tahun_ajaran', 'like', '%' . $search . '%');
            })
            ->when($classLevelFilter, function ($query, $classLevelFilter) {
                return $query->whereHas('classLevels', function ($q) use ($classLevelFilter) {
                    $q->where('class_level.id', $classLevelFilter);
                });
            })
            ->when($guruFilter, function ($query, $guruFilter) {
                return $query->where('guru_id', $guruFilter);
            })
            ->when($mataPelajaranFilter, function ($query, $mataPelajaranFilter) {
                return $query->where('mata_pelajaran', $mataPelajaranFilter);
            })
            ->orderBy('mata_pelajaran', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        // Get data for filter dropdowns
        $classLevels = ClassLevel::orderBy('level')->get();
        $gurus = Guru::orderBy('nama_pendidik')->get();
        $mataPelajarans = Kelas::select('mata_pelajaran')->distinct()->orderBy('mata_pelajaran')->pluck('mata_pelajaran');

        return view('admin.kelas.index', compact('kelas', 'classLevels', 'gurus', 'mataPelajarans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gurus = Guru::orderBy('nama_pendidik')->get();
        $classLevels = ClassLevel::orderBy('level')->get();

        // Variabel $users tidak lagi dilempar ke view, akan di-load via AJAX
        return view('admin.kelas.create', compact('gurus', 'classLevels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi request langsung di controller
        $validator = Validator::make($request->all(), [
            'mata_pelajaran' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:9', // Format: 2024/2025
            'guru_id' => 'required|exists:guru,id',
            'class_level_id' => 'required|array|min:1',
            'class_level_id.*' => 'exists:class_level,id',
            'jadwal_hari' => 'required|string|max:20',
            'users' => 'sometimes|array',
            'users.*' => 'exists:users,id'
        ], [], [
            'mata_pelajaran' => 'Mata Pelajaran',
            'tahun_ajaran' => 'Tahun Ajaran',
            'guru_id' => 'Guru Pengajar',
            'class_level_id' => 'Jenjang Kelas',
            'jadwal_hari' => 'Hari Kelas',
            'users' => 'Santri',
            'users.*' => 'Santri'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validatedData = $validator->validated();

        // Create the class with basic data
        $kelas = Kelas::create([
            'mata_pelajaran' => $validatedData['mata_pelajaran'],
            'tahun_ajaran' => $validatedData['tahun_ajaran'],
            'guru_id' => $validatedData['guru_id'],
            'jadwal_hari' => $validatedData['jadwal_hari'],
        ]);

        // Attach jenjang kelas (many-to-many)
        $kelas->classLevels()->attach($validatedData['class_level_id']);

        // Add students to the class if selected
        if (isset($validatedData['users'])) {
            $kelas->users()->attach($validatedData['users']);
        }

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelas $kela)
    {
        $kela->load(['guru', 'classLevels', 'users']);

        return view('admin.kelas.show', compact('kela'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelas $kela)
    {
        $gurus = Guru::orderBy('nama_pendidik')->get();
        $classLevels = ClassLevel::orderBy('level')->get();

        // Get all students (users with role 'user')
        $users = User::with('classLevel')->get();

        // Get IDs of students already in this class
        $selectedUserIds = $kela->users->pluck('id')->toArray();

        return view('admin.kelas.edit', compact('kela', 'gurus', 'classLevels', 'users', 'selectedUserIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kelas $kela)
    {
        // Validasi request langsung di controller
        $validator = Validator::make($request->all(), [
            'mata_pelajaran' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:9', // Format: 2024/2025
            'guru_id' => 'required|exists:guru,id',
            'class_level_id' => 'required|exists:class_level,id',
            'jadwal_hari' => 'required|string|max:20',
            'users' => 'sometimes|array',
            'users.*' => 'exists:users,id'
        ], [], [
            'mata_pelajaran' => 'Mata Pelajaran',
            'tahun_ajaran' => 'Tahun Ajaran',
            'guru_id' => 'Guru Pengajar',
            'class_level_id' => 'Jenjang Kelas',
            'jadwal_hari' => 'Hari Kelas',
            'users' => 'Santri',
            'users.*' => 'Santri'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validatedData = $validator->validated();

        // Update basic class data
        $kela->update([
            'mata_pelajaran' => $validatedData['mata_pelajaran'],
            'tahun_ajaran' => $validatedData['tahun_ajaran'],
            'guru_id' => $validatedData['guru_id'],
            'jadwal_hari' => $validatedData['jadwal_hari'],
        ]);

        // Update the students in this class
        // Update the class levels (jenjang kelas) for this class
        if (isset($validatedData['class_level_id'])) {
            $kela->classLevels()->sync($validatedData['class_level_id']);
        } else {
            $kela->classLevels()->sync([]);
        }
        if (isset($validatedData['users'])) {
            $kela->users()->sync($validatedData['users']);
        } else {
            // If no users selected, detach all
            $kela->users()->sync([]);
        }

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    /**
     * Mengambil data santri berdasarkan Jenjang Kelas untuk request AJAX.
     */
    public function getSantriByClassLevel($class_level_id)
    {
        // Cari santri (user dengan role 'user') yang sesuai dengan class_level_id
        $santri = \App\Models\User::role('user')
            ->where('class_level_id', $class_level_id)
            ->orderBy('nama_santri')
            ->get(['id', 'nis', 'nama_santri']);

        // Kembalikan data dalam format JSON
        return response()->json($santri);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelas $kela)
    {
        // Detach all students from this class first
        $kela->users()->detach();

        // Delete the class
        $kela->delete();

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}
