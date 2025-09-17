        var canvas = document.getElementById('myCanvas1');
        var canvas2 = document.getElementById('myCanvas2');
        var canvas3 = document.getElementById('myCanvas3');
        var ctx = canvas.getContext('2d');
        var ctx2 = canvas2.getContext('2d');
        var ctx3 = canvas3.getContext('2d');
        let w = 55; let h = 55; let x = 20; let y = 20;

        // borde del canvas
        ctx.strokeRect(0, 0, canvas.width, canvas.height);

        //figuras
        //cuadro 1
        ctx.fillStyle = "#8687E1";
        ctx.fillRect(x, y, w, h);

        //cuadro 2
        ctx.fillStyle = "#C73636";
        ctx.fillRect(x+40, y+40, w, h);

        //cuadro 3
        ctx.fillStyle = "rgba(232, 85, 235, 0.5)"; // Color con transparencia
        ctx.fillRect(x+80, y+80, w, h);
