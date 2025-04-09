<!DOCTYPE html>
<body>
    <h1>Form Tambah Data User</h1>
        <form action="/user/tambah_simpan" method="POST">
            {{csrf_field()}}
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukan Username">
            <br>
            <label>Nama</label>
            <input type="text" name="nama" placeholder="Masukan Nama">
            <br>
            <label>Password</label>
            <input type="text" name="password" placeholder="Masukan Password">
            <br>
            <label>Level ID</label>
            <input type="text" name="level_id" placeholder="Masukan ID Level">
            <br><br>
            <input type="submit" value="Simpan" class="btn btn-success">
        </form>
</body>
</html>