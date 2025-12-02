- Registrasi

1. Registrasi INSERT INTO tamu (no_identitas, nama_tamu, email, no_telepon, password) VALUES -> auth/registrasi.php

- Login Tamu

2. Login Tamu SELECT nama_tamu FROM tamu WHERE no_identitas = ‘1101’ AND password = ‘pass123’ -> auth/login.php

- Isi data admin

3. INSERT INTO admin (nama_lengkap, username, email, password, role) VALUES ... -> admin/registrasi_admin.php (File Baru)

- Login Admin

4. SELECT nama_lengkap FROM admin WHERE username = 'admin1' AND password = 'adminpass1' -> auth/login.php

- Menampilkan Ruangan

5. SELECT nomor_kamar FROM kamar WHERE status = 'belum_dipesan' -> tamu/a-kamar.php

- Memesan Kamar

6. INSERT INTO reservasi (id_reservasi, id_kamar, no_identitas, tgl_check_in, tgl_check_out) VALUES -> tamu/b-proses_reservasi.php

- Update Status Kamar

7. UPDATE kamar SET status = 'dipesan' WHERE nomor_kamar = 101 -> tamu/b-proses_reservasi.php

- Pembayaran

8. INSERT INTO pembayaran (id_reservasi, metode_pembayaran, total_bayar) VALUES -> tamu/b-proses_reservasi.php

- Manajemen Akun

9. SELECT nama_tamu, email, no_telepon FROM tamu -> admin/a-manajemen_tamu.php

- Update Data Tamu

10. UPDATE tamu SET nama_tamu = ..., email = ..., no_telepon = ... WHERE no_identitas = ... -> tamu/c-profile.php

- Menghapus Akun

11. DELETE FROM tamu WHERE no_identitas = 1101 -> admin/a-manajemen_tamu.php

- Checkin

12. UPDATE reservasi SET tgl_check_in = NOW() ... UPDATE kamar SET status = ‘ditempati’ ... -> admin/b-kelola_reservasi.php

- Checkout

13. UPDATE reservasi SET tgl_check_out = NOW() ... UPDATE kamar SET status = ‘belum_dipesan’ ... -> admin/b-kelola_reservasi.php
