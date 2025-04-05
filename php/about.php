<html>
<head>
    <meta charset="UTF-8">
    <title>Documentation</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- bootstrap 3.0.2 -->
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- font Awesome -->
    <link href="../css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="../css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <link href="../js/morris/morris-0.4.3.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="../css/AdminLTE.css" rel="stylesheet" type="text/css" />

    <link rel="shortcut icon" href="../img/favicon.png" />

    <link href="../css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="../css/select2.css" rel="stylesheet" type="text/css" />
    <script src="../js/jquery-1.12.3.js" type="text/javascript"></script>

</head>
<body>
<nav class="navbar navbar-inverse" style="border-radius:0px;">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
    <body class="skin-blue">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="landing_page.php"><img alt="Brand" src="../img/home.png" style="width:50px; margin-top:-15px; "></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="landing_page.php">Home <span class="sr-only">(current)</span></a></li>
        
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

 

<pre> 
  <!--Back Button-->
<script>
function goBack() {
  window.history.back()
}
</script>
</head>
<body>
<button onclick="goBack()">Go Back</button>
</body>
</html>

<pre><h3 class="mb-4">Getting started</h3>
                            <p>You can directly use the compiled and ready-to-use the version of the template. But in case you plan to customize the template extensively the template allows you to do so.</p>
                            <p>Within the download you'll find the following directories and files, logically grouping common assets and providing both compiled and minified variations:</p> </pre>
                            
                            
                        <pre>
                        Spica Free/
                        ├── template/
                              ├── css/
                              ├── fonts/
                              ├── images/
                              ├── js/
                              ├── pages/
                              ├── partials/
                              ├── index.html
                              ├── scss/
                              ├── vendors/
                              ├── gulpfile.js
                              ├── package.json                           
                              ├── docs/
                        ├── CHANGELOG.md</pre> 


<pre>
<p class="mt-1">Note: The root folder denoted further in this documentation refers to the 'template' folder inside the downloaded folder</p>
<div class="alert alert-success mt-4 d-flex align-items-center" role="alert">
<i class="ti-info-alt mr-4"></i>
<p>We have bundled up the vendor files needed for demo purpose into a folder 'vendors', you may not need all those vendors or may need to add more vendors in your application.
If you want to make any change in the vendor package files, you can either change the src path for related tasks in the file gulpfile.js and run the task <code> bundleVendors </code> 
to rebuild the vendor files or manually edit the vendor folder.</p>
</div>
<hr class="mt-5">
<h4 class="mt-4">Installation</h4>
<p class="mb-0">
You need to install package files/Dependencies for this project if you want to customize it. To do this, you must have <span class="font-weight-bold">node and npm</span> installed in your computer.
</p>
<p class="mb-0">Installation guide of the node can be found <a href="https://nodejs.org/en/">here</a>. As npm comes bundled with a node, a separate installation of npm is not needed.</p>
<p>
If you have installed them, just go to the root folder and run the following command in your command prompt or terminal (for the mac users).
</p>
<pre class="shell-mode">
npm install</pre>

<p class="mt-4">
This will install the dev dependencies in the local <span class="font-weight-bold">node_modules</span> folder in your root directory.
</p>
<p class="mt-2">
Then you will need to install <span class="font-weight-bold">Gulp</span>. We use the Gulp task manager for the development processes. 
Gulp will watch for changes to the SCSS files and automatically compile the files to CSS.
</p>
<p>Getting started with Gulp is pretty simple. The <a href="https://gulpjs.com/" target="_blank">Gulp</a> 
site is a great place to get information on installing Gulp if you need more information. You need to first install Gulp-cli in your machine using the below command.</p>
<pre class="shell-mode">

npm install -g gulp-cli</pre>

<p class="mt-4">This installs Gulp-cli globally to your machine. The other thing that Gulp requires, which, is really what does all the work, is the gulpfile.js. 
In this file, you set up all of your tasks that you will rn.</p>
<p>Don't worry. We have this file already created for you!</p>
<p>To run this project in development mode enter the following command below. 
This will start the file watch by gulp and whenever a file is modified, the SCSS files will be compiled to create the CSS file.</p>

<pre class="shell-mode">
gulp serve</pre>           

<div class="alert alert-warning mt-4" role="alert">
<i class="ti-info-alt-outline"></i>It is important to run <code>gulp serve</code> command from the directory where the gulpfile.js is located.
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</pre>




 

<script src="../js/alert.js" type="text/javascript"></script>
<script src="../js/bootstrap.min.js" type="text/javascript"></script>


<script src="../js/morris/raphael-2.1.0.min.js" type="text/javascript"></script>
<script src="../js/morris/morris.js" type="text/javascript"></script>
<script src="../js/select2.full.js" type="text/javascript"></script>

<script src="../js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="../js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="../js/buttons.print.min.js" type="text/javascript"></script>

<script src="../js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<!-- AdminLTE App -->
<script src="../js/AdminLTE/app.js" type="text/javascript"></script>

<script type="text/javascript">
  $(function() {
      $("#table").dataTable({
         "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 0,5 ] } ],"aaSorting": []
      });
  });
</script>
</html>