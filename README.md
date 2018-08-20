<html>
  <body style="font-family:Arial;">
    <center>
      <div style="margin-top:100px;">
        <h1>Authentication System</h1>
        <h2>by Christian Carpineta</h2>
        <div style="border-bottom:2px solid grey;"></div>
        <div style="margin-top:50px;width:500px;height:auto;text-align:justify;">
          <span>This system, written in PHP, is very simple and intuitive to use. It can be implemented and modified very easily. The                     system is connected to a MySQL database where all the data entered by the user are saved in a safe way. When you register,                 a confirmation email is sent to the user, who will not be able to confirm the protected pages (index.php). Furthermore,                   each time a login is made, the last IP and the last access date are saved. There is also the system to recover the                         password, made safe thanks to various controls implemented.
          </span>
        </div>
        <div>
          <h3>Usage</h3>
          <div style="border-bottom:2px solid grey;"></div>
          <div id="usage" style="margin-top:50px;width:500px;height:auto;text-align:justify;">
            <span>To be able to use the system, you must first load the "users.sql" table contained in the folder, in the database. Next,                   you need to configure the "$ connection" variable contained in each file, and lastly, change the address of the "mail                     ()" function on all the pages that require it.
            </span>
          </div>
        </div>
        <div>
          <h3>License</h3>
          <div style="border-bottom:2px solid grey;"></div>
          <div id="license" style="margin-top:50px;width:500px;height:auto;text-align:justify;">
            <span>Copyright 2018 - Christian Carpineta</span>
          </div>
        </div>
      </div>
    </center>
</html>
