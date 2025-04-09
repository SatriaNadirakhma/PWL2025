<!DOCTYPE html>
<body>
<h1>Form Ubah Data User</h1>
    <a href="/user">Kembali</a>
    <br><br>

        <form action="/user/ubah_simpan/{{ $data->user_id }}" method="POST">
            
            {{csrf_field()}}
            {{method_field('PUT')}}
            
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukan Username" value="{{$data->username}}">
            <br>
            <label>Nama</label>
            <input type="text" name="nama" placeholder="Masukan Nama" value="{{$data->nama}}">
            <br>
            <label>Password</label>
            <input type="text" name="password" placeholder="Masukan Password" value="{{$data->password}}">
            <br>
            <label>Level ID</label>
            <input type="text" name="level_id" placeholder="Masukan ID Level" value="{{$data->level_id}}">
            <br><br>
            <input type="submit" value="Simpan" class="btn btn-success">
        </form>
        
</body>
</html>