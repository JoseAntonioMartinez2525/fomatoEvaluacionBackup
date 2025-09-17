 function evaluarCalidad(total)
    {
        switch (true) {
            case (total >= 210 && total <= 264.99):
                return 'I';
            case (total >= 265 && total <= 319.99):
                return 'II';
            case (total >= 320 && total <= 374.99):
                return 'III';
            case (total >= 375 && total <= 429.99):
                return 'IV';
            case (total >= 430 && total <= 484.99):
                return 'V';
            case (total >= 485 && total <= 539.99):
                return 'VI';
            case (total >= 540 && total <= 594.99):
                return 'VII';
            case (total >= 595 && total <= 649.99):
                 return 'VIII';
            case (total >= 650 && total <= 700):
                return 'IX';
            default:
                return 'FALSE';
        }
    }

    // Función para evaluar el total
    function evaluarTotal(totalComisionRepetido)
    {
        switch (true) {
            case (totalComisionRepetido >= 301 && totalComisionRepetido <= 377.99):
                return 'I';
            case (totalComisionRepetido >= 378 && totalComisionRepetido <= 455.99):
                return 'II';
            case (totalComisionRepetido >= 456 && totalComisionRepetido <= 533.99):
                return 'III';
            case (totalComisionRepetido >= 534 && totalComisionRepetido <= 611.99):
                return 'IV';
            case (totalComisionRepetido >= 612 && totalComisionRepetido <= 689.99):
                return 'V';
            case (totalComisionRepetido >= 690 && totalComisionRepetido <= 767.99):
                return 'VI';
            case (totalComisionRepetido >= 768 && totalComisionRepetido <= 845.99):
                return 'VII';
            case (totalComisionRepetido >= 846 && totalComisionRepetido <= 923.99):
                return 'VIII';
            case (totalComisionRepetido >= 924 && totalComisionRepetido <= 1000):
                return 'IX';
            default:
                return 'FALSE';
        }
    }