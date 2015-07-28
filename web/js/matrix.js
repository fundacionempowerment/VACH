function doMatrix(context, matrixData)
{
    var data = matrixData['data'];
    var memberId = matrixData['memberId'];
    var width = 800;
    var height = 400;
    var horizontalMargin = 120;
    var matrixData = new Array();
    var minx = horizontalMargin;
    var minProductivity = 10000;
    var maxx = width - horizontalMargin;
    var maxProductivity = -10000;
    var maxConsciousness = -100000;

    var avgProductivity = 0;
    var avgConsciousness = 0;
    var current_value = 0;
    var sumConsciousness = 0;
    var sumProductivity = 0;

    for (var i in data) {
        if (data[i]['productivity'] < minProductivity)
            minProductivity = data[i]['productivity'];
        if (data[i]['productivity'] > maxProductivity)
            maxProductivity = data[i]['productivity'];
        if (Math.abs(data[i]['consciousness']) > maxConsciousness)
            maxConsciousness = Math.abs(data[i]['consciousness']);

        sumConsciousness = sumConsciousness + data[i]['consciousness'];
        sumProductivity = sumProductivity + data[i]['productivity'];
    }

    var deltax = maxx - minx;
    var deltaProductivity = maxProductivity - minProductivity;

    avgProductivity = sumProductivity / data.length;
    avgConsciousness = sumConsciousness / data.length;
    sumConsciousness = 0;

    for (var i in data) {
        current_value = data[i]['consciousness'];
        sumConsciousness = sumConsciousness + Math.pow((current_value - avgConsciousness), 2);
    }
    var stardarDeviation = Math.sqrt(sumConsciousness / (data.length - 1)); //standar deviation

    var maxy = (Math.floor((maxConsciousness + 1) / 20) + 1) * 20;

    for (var i in data) {
        var posx = Math.floor((data[i]['productivity'] - minProductivity) / deltaProductivity * deltax + minx);
        var posy = Math.floor((maxy - data[i]['consciousness']) * height / 2 / maxy);
        var valueToPush = new Array();
        valueToPush.push(data[i]['name']);
        valueToPush.push(posx);
        valueToPush.push(posy);
        valueToPush.push(data[i]['id']);
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
    context.font = '12pt Helvetica';
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
    posx = (avgProductivity - minProductivity) / deltaProductivity * deltax + minx;
    context.strokeStyle = '#5a9bbc';
    context.beginPath();
    context.moveTo(0, height / 2);
    context.lineTo(width, height / 2);
    context.moveTo(posx, 0);
    context.lineTo(posx, height - 26);
    context.moveTo(minx, 0);
    context.lineTo(minx, height - 26);
    context.moveTo(maxx, 0);
    context.lineTo(maxx, height - 26);
    context.stroke();

    //axes values
    context.font = '11pt Helvetica';
    context.fillStyle = '#496987';
    context.textAlign = 'center';
    context.textBaseline = 'bottom';
    context.fillText('Min: ' + Math.round(minProductivity * 10) / 10 + ' %', minx, height - 5);
    context.fillText('Max: ' + Math.round(maxProductivity * 10) / 10 + ' %', maxx, height - 5);
    context.fillText('Prom: ' + Math.round(avgProductivity * 10) / 10 + ' %', posx, height - 5);

    context.textAlign = 'center';
    context.textBaseline = 'top';
    for (i in matrixData) {
        context.beginPath();
        if (matrixData[i][3] == memberId)
            context.lineWidth = 5;
        else
            context.lineWidth = 1;
        context.arc(matrixData[i][1], matrixData[i][2], 12, 0, 2 * Math.PI, false);

        if (Math.abs(data[i]['consciousness']) < stardarDeviation)
            context.fillStyle = '#5bc0de';
        else
            context.fillStyle = '#f0ad4e';
        context.fill();
        context.strokeStyle = '#5a9bbc';
        context.stroke();

        if (matrixData[i][3] == memberId)
            context.font = 'bold 11pt Helvetica';
        else
            context.font = '11pt Helvetica';

        context.fillStyle = '#496987';
        context.fillText(matrixData[i][0], matrixData[i][1], matrixData[i][2] + 16);
    }
}
