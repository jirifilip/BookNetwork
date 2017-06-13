<?php

echo "Till shade is gone, till water is gone";

$keys = ["phpScript", "action", "fileToCreate", "currFolder"];
extract( array_combine($keys, $argv) );

if ($action == "controller") {
    $fileToCreate .= "Controller";
    $file = "<?php

    class $fileToCreate extends Controller {

        function __construct() {
        }
    
        public function index() {
            echo \"$fileToCreate index method\"; 
        }

        public function create() {
            echo \"$fileToCreate create method\"; 
        }

        public function show() {
            echo \"$fileToCreate show method\"; 
        }

        public function store() {
            echo \"$fileToCreate store method\"; 
        }

        public function load() {
            echo \"$fileToCreate edit method\"; 
        }

        public function edit() {
            echo \"$fileToCreate edit method\"; 
        }

        public function destroy() {
            echo \"$fileToCreate destroy method\"; 
        }

    }
    ";    

    file_put_contents("$currFolder/controllers/$fileToCreate.php", $file);
}

else if ($action == "model") {
    $modelName = strtolower($fileToCreate); 
    $file = "<?php
    
    class $fileToCreate extends Model {
    
            function __construct() {
                \$this->name = '$modelName';
            }

    }
    ";    
    file_put_contents("$currFolder/models/$fileToCreate.php", $file);
}

else if ($action == "guard") {
    $fileToCreate .= "Guard";

    $file = "<?php
    
    class $fileToCreate implements IGuard {
    
            public function resolve(array \$data) {
                return true;
            }

    }
    ";    
    file_put_contents("$currFolder/guards/$fileToCreate.php", $file);
}

else if ($action == "view") {
    $file = "<html>
        <head>
            <title>$fileToCreate</title>
        </head>
        <body>
            
        </body>
    </html>
    ";    
    file_put_contents("$currFolder/views/$fileToCreate.phtml", $file);
}