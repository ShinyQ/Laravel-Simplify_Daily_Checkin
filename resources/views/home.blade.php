<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Checkin Simplify</title>
        <link type="text/css" href="{{url('assets/bootstrap.min.css')}}" rel="stylesheet">
    </head>
    <body>

        <div class="container" style="margin-top: 50px">
            <h1>Checkin Simplier <font style="font-size: 20px">(Checkin With One Click)</font></h1>
            <small class="form-text text-muted">By ShinyQ</small>

            <div class="row" style="margin-top: 30px">
              <div class="col-md-6">
                <p style="margin-bottom: 20px; text-align: justify">
                  <b><font color="red">*Note : (FOR IF-43-09 ONLY)</font></b> Username dan Password yang digunakan adalah SSO Telkom University (Akun Igracias).
                   Tambah Data Hanya Perlu Dilakukan Sekali Untuk Proses Autentikasi sebelum Checkin dan hanya akan disimpan di perangkat lokal anda.
                   <br>Based Checkin Site : (<a target="_blank" href="https://checkin.telkomuniversity.ac.id/">https://checkin.telkomuniversity.ac.id/</a>)
                </p>
                  <form id="form_sub" onsubmit="setLocalStorage()">
                    @csrf
                    <div class="form-group">
                      <label for="exampleInputEmail1">Username:</label>
                      <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan Username">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Password:</label>
                      <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan Password">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Kota Asal:</label>
                      <input type="text" id="city" name="city" class="form-control" placeholder="Masukkan Kota Asal">
                    </div>
                    <input type="submit" class="btn btn-primary  pull-right" value="Tambah Data" />
                  </form>
              </div>

              <div id="check-div" class="col-md-6" style="display: none">
                  <div style="margin-top: 50px; margin-bottom: 50px;">
                    <center>
                        <h5>Klik Untuk Mengecek Status Checkin Hari Ini</h5>
                        <b>({{ date("l") }}, {{ date("Y-m-d") }})</b><br><br>
                        <form class="" id="get-checkin" action="" onsubmit="setGetStatus()" method="get">
                            @csrf
                            <input style="margin-bottom: 20px" class="btn btn-primary" type="submit" name="" value="Cek Status Checkin">
                        </form>
                        @if($GetStatus == "not-acceptable")
                          <p style="color:green; font-size: 20px"><b>Kamu Sudah Melakukan Checkin hari Ini<b></p>
                        @elseif($GetStatus == "acceptable")
                          <p style="color:red; font-size: 20px"><b>Kamu Belum Melakukan Checkin hari Ini<b></p>
                          <form class="" id="checkin" action="" onsubmit="checkin()" method="POST">
                              @csrf
                              <input style="margin-bottom: 20px" class="btn btn-primary" type="submit" name="" value="Checkin">
                          </form>
                        @endif
                        @if($Checkin == "Success")
                          <p style="color:green; font-size: 20px"><b>Check In Success<b></p>
                        @endif
                    </center>
                  </div>
              </div>

            </div>
        </div>
        <script type="text/javascript">
          if(localStorage.getItem('username') != null){
            document.getElementById("check-div").style.display = "inline";
          }
          document.getElementById("username").value = localStorage.getItem('username');
          document.getElementById("password").value = localStorage.getItem('password');
          document.getElementById("city").value = localStorage.getItem('city');

          function setLocalStorage() {
            var username = document.getElementById("username").value;
            var password = document.getElementById("password").value;
            var city = document.getElementById("city").value;
            localStorage.setItem('username', username);
            localStorage.setItem('password', password);
            localStorage.setItem('city', city);
            alert("Sukses Mengupdate Data Pada Penyimpanan Lokal");
          }

          function setGetStatus(){
            var username = localStorage.getItem('username');
            var password = localStorage.getItem('password');
            document.getElementById('get-checkin').action = "/status_checkin/"+username+"/"+password;
          }

          function checkin(){
            var username = localStorage.getItem('username');
            var password = localStorage.getItem('password');
            var city = localStorage.getItem('city');
            document.getElementById('checkin').action = "/checkin/"+username+"/"+password+"/"+city;
          }
        </script>
    </body>
</html>
