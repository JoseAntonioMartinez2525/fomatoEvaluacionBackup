    // Function to check if there is an observation for a specific activity
    function hayObservacion(actividad) {
      const obs = document.querySelector(`#obs${actividad}`).value;
      return obs.trim() !== '';
    }

    function minWithSum(value1, value2) {
      const sum = value1 + value2;
      return Math.min(sum, 200);


    }

    function min40(...values) {
      const sum40 = values.reduce((acc, val) => acc + val, 0);
      return Math.min(sum40, 40);
    }

    function min30(...values) {
      const sum30 = values.reduce((acc, val) => acc + val, 0);
      return Math.min(sum30, 30);
    }

    function subtotal(value1, value2) {
      const st = value1 * value2;
      return st;
    }

    function min60(...values) {
      const sum60 = values.reduce((acc, val) => acc + val, 0);
      return Math.min(sum60, 60);
    }

    function minWithSumThree(value1, value2, value3, value4) {
      const ms = value1 + value2 + value3 + value4;
      return Math.min(ms, 100);
    }

    function min50(...values) {
      const ms = values.reduce((acc, val) => acc + val, 0);
      return Math.min(ms, 50);
    }

    function minWithSumThreeFive(value1, value2) {
      const ms = value1 + value2;
      return Math.min(ms, 75);
    }

    function minTutorias() {
      // convert the arguments object to an array
      const values = Array.from(arguments);

      // use reduce to sum the values
      const ms = values.reduce((acc, current) => {
        return acc + current;
      }, 0);

      // return the minimum of ms and 200
      return Math.min(ms, 200);
    }

    function min700(...values) {
      const ms = values.reduce((acc, val) => acc + val, 0);
      return Math.min(ms, 700);
    }

    // Función para actualizar el objeto data con los valores de los campos del formulario
    function actualizarData() {
      data[this.id] = this.value;
    }