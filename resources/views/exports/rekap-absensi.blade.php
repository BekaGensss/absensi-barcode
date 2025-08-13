<table>
    <thead>
        <tr>
            <th colspan="6">Rekap Absensi Bulanan - Kelas: {{ $kelas->nama_kelas }}</th>
        </tr>
        <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Hadir</th>
            <th>Sakit</th>
            <th>Izin</th>
            <th>Alfa</th>
        </tr>
    </thead>
    <tbody>
        @foreach($absensi_rekap as $siswa_id => $absen_data)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $absen_data->first()->siswa->nama_siswa ?? '-' }}</td>
                <td>{{ $absen_data->where('status', 'Hadir')->count() }}</td>
                <td>{{ $absen_data->where('status', 'Sakit')->count() }}</td>
                <td>{{ $absen_data->where('status', 'Izin')->count() }}</td>
                <td>{{ $absen_data->where('status', 'Alfa')->count() }}</td>
            </tr>
        @endforeach
    </tbody>
</table>