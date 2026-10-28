<?php

namespace App\Models;

use Database\Factories\SiswaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Carbon;
#[Fillable(['user_id', 'nisn', 'nama', 'kelas', 'jenis_kelamin', 'tanggal_lahir', 'alamat'])]
class Siswa extends Model
{
    /** @use HasFactory<SiswaFactory> */
    use HasFactory;
    protected $table = 'siswa';
    /**
    * Tanggal lahir: disimpan sebagai Y-m-d, dibaca sebagai Carbon.
    *
    * @return Attribute<Carbon, string>
    */
    protected function tanggalLahir(): Attribute
    {
        return Attribute::make(
        get: fn (?string $value) => $value === null ? null : Carbon::parse($value),
        set: fn ($value) => $value === null ? null : Carbon::parse($value)->toDateString(),
        );
    }
   
    /**
    * Email akun login milik siswa.
    */
    public function emailAkun(): ?string
    {
        return User::where('id', $this->user_id)->value('email');
    }
   
    /**
    * Rekap jumlah absensi per status beserta persentase kehadiran.
    *
    * @return array<string, int>
    */
    public function rekapAbsensi(): array
    {
        $counts = Absensi::where('siswa_id', $this->id)
        ->selectRaw('status, count(*) as total')
        ->groupBy('status')
        ->pluck('total', 'status');
    
        $rekap = [];
        
        foreach (array_keys(Absensi::STATUS) as $status) {
            $rekap[$status] = (int) ($counts[$status] ?? 0);
        }
        
        $rekap['total'] = array_sum($rekap);
        $rekap['persentase'] = $rekap['total'] > 0
            ? (int) round($rekap['hadir'] / $rekap['total'] * 100)
            : 0;
    
        return $rekap;
    }
    
    public function jenisKelaminLabel(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }
}