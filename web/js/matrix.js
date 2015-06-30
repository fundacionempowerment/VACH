function doMatrix(context, data)
{
    var width = 350 * 1.5;
    var height = 250;
    var matrixData = new Array();
    var minProductivity = 10000;
    var maxProductivity = -10000;
    var maxConsciousness = -100000;

    var average = 0;
    var current_value = 0;
    var sum = 0;

    for (var i in data) {
        if (data[i]['productivity'] < minProductivity)
            minProductivity = data[i]['productivity'];
        if (data[i]['productivity'] > maxProductivity)
            maxProductivity = data[i]['productivity'];
        if (Math.abs(data[i]['consciousness']) > maxConsciousness)
            maxConsciousness = Math.abs(data[i]['consciousness']);

        sum = sum + data[i]['consciousness'];
    }

    average = sum / data.length;
    sum = 0;

    for (var i in data) {
        current_value = data[i]['consciousness'];
        sum = sum + Math.pow((current_value - average), 2);
    }
    var stardarDeviation = Math.sqrt(sum / (data.length - 1)); //standar deviation

    var minx = Math.floor((minProductivity - 1) / 10) * 10;
    var maxx = (Math.floor((maxProductivity + 1) / 10) + 1) * 10;
    var maxy = (Math.floor((maxConsciousness + 1) / 10) + 1) * 10;

    for (var i in data) {
        var posx = Math.floor((data[i]['productivity'] - minx) / (maxx - minx) * width);
        var posy = Math.floor((maxy - data[i]['consciousness']) * height / 2 / maxy);
        var valueToPush = new Array();
        valueToPush.push(data[i]['name']);
        valueToPush.push(posx);
        valueToPush.push(posy);
        matrixData.push(valueToPush);
    }

    var goodConsciousness = Math.floor(stardarDeviation * height / 2 / maxy);

    //high conciouness zone
    context.beginPath();
    context.rect(0, height / 2 - goodConsciousness, width, goodConsciousness * 2);
    context.fillStyle = '#d9edf7';
    context.fill();
    context.strokeStyle = '#5a9bbc';
    context.stroke();

    // do cool things with the context
    context.fillStyle = 'red';
    context.textAlign = 'left';
    context.textBaseline = 'top';
    context.fillText('BC/BP+', 5, 5);
    context.textBaseline = 'bottom';
    context.fillText('AC/BP', 5, height / 2 - 2);
    context.textAlign = 'left';
    context.textBaseline = 'bottom';
    context.fillText('BC/BP-', 5, height - 5);
    context.textAlign = 'right';
    context.textBaseline = 'top';
    context.fillText('BC/AP+', width - 5, 5);
    context.textBaseline = 'bottom';
    context.fillText('AC/AP', width - 5, height / 2 - 2);
    context.textAlign = 'right';
    context.textBaseline = 'bottom';
    context.fillText('BC/AP-', width - 5, height - 5);


    //axes
    context.strokeStyle = '#5a9bbc';
    context.beginPath();
    context.moveTo(0, height / 2);
    context.lineTo(width, height / 2);
    context.moveTo(width / 2, 0);
    context.lineTo(width / 2, height);
    context.stroke();

    for (i in matrixData) {
        context.beginPath();
        context.arc(matrixData[i][1], matrixData[i][2], 12, 0, 2 * Math.PI, false);

        if (Math.abs(data[i]['consciousness']) < stardarDeviation)
            context.fillStyle = '#5bc0de';
        else
            context.fillStyle = '#f0ad4e';
        context.fill();
        context.strokeStyle = '#5a9bbc';
        context.stroke();

        context.fillStyle = '#496987';
        context.textAlign = 'center';
        context.fillText(matrixData[i][0], matrixData[i][1], matrixData[i][2] + 24);
    }
}
