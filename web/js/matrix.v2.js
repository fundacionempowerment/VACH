function doMatrix(context, matrixData, absolute)
{
    var data = matrixData['data'];
    var memberId = matrixData['memberId'];
    var width = 800;
    var height = 400;
    var bottomMargin = 26;
    var horizontalMargin = 105;
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
    if (absolute == true) {
        minProductivity = 0;
        maxProductivity = 100;
    }

    avgProductivity = sumProductivity / data.length;

    var deltax = maxx - minx;
    var deltaProductivity = maxProductivity - minProductivity;

    avgConsciousness = sumConsciousness / data.length;
    sumConsciousness = 0;

    for (var i in data) {
        sumConsciousness = sumConsciousness + Math.abs(data[i]['consciousness']);
    }
    var stardarDeviation = sumConsciousness / (data.length);

    var maxy = (Math.floor((maxConsciousness + 1) / 10) + 1.1) * 10;

    for (var i in data) {
        var posx = Math.floor((data[i]['productivity'] - minProductivity) / deltaProductivity * deltax + minx);
        var posy = Math.floor((maxy - data[i]['consciousness']) * (height - bottomMargin) / 2 / maxy);
        var valueToPush = new Array();
        valueToPush.push(data[i]['name']);
        valueToPush.push(posx);
        valueToPush.push(posy);
        valueToPush.push(data[i]['id']);
        matrixData.push(valueToPush);
    }

    var goodConsciousnessY1 = Math.floor((maxy - stardarDeviation) * (height - bottomMargin) / 2 / maxy);
    var goodConsciousnessY2 = Math.floor((maxy + stardarDeviation) * (height - bottomMargin) / 2 / maxy);
    var goodConsciousnessYAxe = Math.floor((height - bottomMargin) / 2);

    //high conciouness zone
    context.beginPath();
    if (absolute) {
        context.rect(horizontalMargin, goodConsciousnessY1, width - horizontalMargin * 2, goodConsciousnessY2 - goodConsciousnessY1);
    } else {
        context.rect(0, goodConsciousnessY1, width, goodConsciousnessY2 - goodConsciousnessY1);
    }
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
    context.fillText('AC/BP', 5, goodConsciousnessYAxe - 2);
    context.textAlign = 'left';
    context.textBaseline = 'bottom';
    context.fillText('BC/BP-', 5, height - 5);
    context.textAlign = 'right';
    context.textBaseline = 'top';
    context.fillText('BC/AP+', width - 5, 5);
    context.textBaseline = 'bottom';
    context.fillText('AC/AP', width - 5, goodConsciousnessYAxe - 2);
    context.textAlign = 'right';
    context.textBaseline = 'bottom';
    context.fillText('BC/AP-', width - 5, height - 5);

    //axes
    posx = (avgProductivity - minProductivity) / deltaProductivity * deltax + minx;
    context.strokeStyle = '#5a9bbc';
    context.beginPath();
    if (absolute) {
        context.moveTo(horizontalMargin, goodConsciousnessYAxe);
        context.lineTo(width - horizontalMargin, goodConsciousnessYAxe);
    } else {
        context.moveTo(0, goodConsciousnessYAxe);
        context.lineTo(width, goodConsciousnessYAxe);
    }
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
    context.lineWidth = 1;
    context.font = '11pt Helvetica';

    for (i in matrixData)
        if (matrixData[i][3] != memberId) {
            context.beginPath();

            context.arc(matrixData[i][1], matrixData[i][2], 12, 0, 2 * Math.PI, false);

            if (Math.abs(data[i]['consciousness']) < stardarDeviation)
                context.fillStyle = '#5cb85c';
            else
                context.fillStyle = '#f0ad4e';
            context.fill();
            context.strokeStyle = '#5a9bbc';
            context.stroke();
            context.fillStyle = '#496987';
            context.fillText(matrixData[i][0], matrixData[i][1], matrixData[i][2] + 16);
        }

    context.lineWidth = 6;
    context.font = 'bold 11pt Helvetica';
    for (i in matrixData)
        if (matrixData[i][3] == memberId)
        {
            context.beginPath();

            context.arc(matrixData[i][1], matrixData[i][2], 12, 0, 2 * Math.PI, false);

            if (Math.abs(data[i]['consciousness']) < stardarDeviation)
                context.fillStyle = '#5cb85c';
            else
                context.fillStyle = '#f0ad4e';
            context.fill();
            context.strokeStyle = '#5a9bbc';
            context.stroke();
            context.fillStyle = '#496987';
            context.fillText(matrixData[i][0], matrixData[i][1], matrixData[i][2] + 16);
        }
}
