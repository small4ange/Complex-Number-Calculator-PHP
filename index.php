<?php
// complex number = k*i + b
    
    session_start();
    if(!isset($_SESSION['history'])) {
        $_SESSION['history'] = [];
    }
    // $_SESSION['history'] = [];

    class Complex {
        private int $b;
        private int $k;

        public function __construct(int $b, int $k) {
            $this->b =  $b;
            $this->k = $k;
        }

        public function stringComplex() {
            return "{$this->b} " . ($this->k >= 0 ? "+" : "") . "{$this->k}i";
        }

        public function plusComplex (Complex $y) {
            return new Complex($this->b + $y->b, $this->k + $y->k);
        }

        public function minusComplex (Complex $y) {
            return new Complex($this->b - $y->b, $this->k - $y->k);
        }

        public function multiplyComplex (Complex $y) {
            return new Complex($this->b * $y->b - $this->k * $y->k, $this->k * $y->b + $y->k * $this->b);
        }

    }
    function Calculation (Complex $x, Complex $y, $operation){
        switch ($operation) {
            case '+':
                return $x->plusComplex($y);
                break;
            case '-':
                return $x->minusComplex($y);
                break;
            case 'x':
                return $x->multiplyComplex($y);
                break;
        }
    }

    function historyHTML() {
        $output = "<ul class='history'>\n";
        if(!empty($_SESSION['history'])) {
            foreach ($_SESSION['history'] as $equation) {
                $output .= "<li>" . htmlspecialchars($equation) . "</li>\n";
            }
        }
        $output .= "</ul>\n";
        return $output;
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $x = new Complex(intval($_POST['x-real']), intval($_POST['x-i']));
        $y = new Complex(intval($_POST['y-real']), intval($_POST['y-i']));
        $operation = $_POST['operation'];

        $result = Calculation($x, $y, $operation);
        $newHistory = $x->stringComplex() . " " . $operation . " " . $y-> stringComplex() . " = " . $result->stringComplex();

        if (!isset($_SESSION['history']) || end($_SESSION['history']) !== $newHistory) {
            $_SESSION['history'][] = $newHistory;
        }
    }

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Complex numbers calculator</title>
</head>
<body>
    <div class="title-div">
        <h1 id="title">Калькулятор комплексных чисел</h1>
    </div>
    
    <div class="container">
        <div class="calculator">
            <form action="" method="post">
                <div class="x">
                    <input type="text" name="x-real" placeholder="0">
                    <input type="text" name="x-i" placeholder="0">
                    <p class="i">i</p>
                </div>
                <select name="operation">
                        <option value="+">+</option>
                        <option value="-">-</option>
                        <option value="x">x</option>
                </select>
                <div class="y">
                    <input type="text" name="y-real" placeholder="0">
                    <input type="text" name="y-i" placeholder="0">
                    <p class="i">i</p>
                </div>
                
                <input type="submit" name="equals" value="=">
            </form>
        </div>
        <div class="history-container">
            <h1 class="history-title">История операций</h1>
            <?php echo historyHTML(); 
            // foreach ($_SESSION['history'] as $e) {
            //     echo $e;
            // }?>
        </div>
    </div>
</body>
</html>
