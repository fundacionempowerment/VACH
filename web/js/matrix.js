function doMatrix(context, data)
{
    var width = 350 * 1.5;
    var height = 200;
    var matrixData = new Array();
    var minProductivity = 10000;
    var maxProductivity = -10000;
    var maxConsciousness = -100000;
    for (var i in data) {
        if (data[i]['productivity'] < minProductivity)
            minProductivity = data[i]['productivity'];
        if (data[i]['productivity'] > maxProductivity)
            maxProductivity = data[i]['productivity'];
        if (Math.abs(data[i]['consciousness']) > maxConsciousness)
            maxConsciousness = Math.abs(data[i]['consciousness']);
    }

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

    // do cool things with the context
    context.fillStyle = 'red';
    context.textAlign = 'left';
    context.textBaseline = 'top';
    context.fillText('BC/BP+', 5, 5);
    context.textAlign = 'left';
    context.textBaseline = 'bottom';
    context.fillText('BC/BP-', 5, height - 5);
    context.textAlign = 'right';
    context.textBaseline = 'top';
    context.fillText('BC/AP+', width - 5, 5);
    context.textAlign = 'right';
    context.textBaseline = 'bottom';
    context.fillText('BC/AP-', width - 5, height - 5);
    //frame
    context.beginPath();
    context.moveTo(0, 0);
    context.lineTo(0, height);
    context.lineTo(width, height);
    context.lineTo(width, 0);
    context.lineTo(0, 0);
    context.stroke();
    //high conciouness zone
    context.beginPath();
    context.rect(0, height / 2 - 10, width, 20);
    context.fillStyle = '#d9edf7';
    context.fill();
    context.strokeStyle = '#5a9bbc';
    context.stroke();
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
        context.arc(matrixData[i][1], matrixData[i][2], 15, 0, 2 * Math.PI, false);
        context.fillStyle = '#eea8a8';
        context.fill();
        context.strokeStyle = '#5a9bbc';
        context.stroke();

        context.fillStyle = '#496987';
        context.textAlign = 'center';
        context.fillText(matrixData[i][0], matrixData[i][1], matrixData[i][2] + 25);
    }
}
