function canvas_arrow(context, fromx, fromy, tox, toy) {
    context.lineWidth = 3;
    context.lineCap = 'round';

    var headlen = 10; // length of head in pixels
    var angle = Math.atan2(toy - fromy, tox - fromx);
    context.beginPath();
    context.moveTo(fromx, fromy);
    context.lineTo(tox, toy);
    context.lineTo(tox - headlen * Math.cos(angle - Math.PI / 6), toy - headlen * Math.sin(angle - Math.PI / 6));
    context.moveTo(tox, toy);
    context.lineTo(tox - headlen * Math.cos(angle + Math.PI / 6), toy - headlen * Math.sin(angle + Math.PI / 6));
    context.stroke();
}

function doRelations(context, data)
{
    // This code asume squere proto human image
    var width = 400;
    var height = 300;
    var imageSize = 30;
    var margin = 20;
    var arraw_ring = 40;
    var xradio = width / 2 - imageSize / 2 - margin;
    var yradio = height / 2 - imageSize / 2 - margin;
    var imageObj = new Image();
    imageObj.src = '/images/protoMale.png';
    imageObj.onload = function() {
        // draw central image
        context.drawImage(imageObj, (width - imageSize) / 2, (height - imageSize) / 2);
        context.textBaseline = 'top';
        context.textAlign = 'center';
        context.fillText(data[0]['name'] + ' ' + data[0]['surname'],
                width / 2,
                height / 2 + 20);

        for (i in data) {
            if (i > 0)
                var current_angle = (i - 1) * 2 * Math.PI / data.length;
            // draw member image
            context.drawImage(imageObj,
                    width / 2 + xradio * Math.cos(current_angle) - imageSize / 2,
                    height / 2 + yradio * Math.sin(current_angle) - imageSize / 2);

            if (data[i]['value'] < 1.6)
                context.strokeStyle = '#d9534f';
            else if (data[i]['value'] > 2.8)
                context.strokeStyle = '#5cb85c';
            else
                context.strokeStyle = '#f0ad4e';
            canvas_arrow(context,
                    width / 2 + (xradio - arraw_ring) * Math.cos(current_angle),
                    height / 2 + (yradio - arraw_ring) * Math.sin(current_angle),
                    width / 2 + arraw_ring * Math.cos(current_angle),
                    height / 2 + arraw_ring * Math.sin(current_angle));

            context.textBaseline = 'top';
            context.textAlign = 'center';
            context.fillText(data[i]['name'] + ' ' + data[i]['surname'],
                    width / 2 + xradio * Math.cos(current_angle),
                    height / 2 + yradio * Math.sin(current_angle) + 20);

            context.fillText(Math.round(data[i]['value'] * 100 / 4) + ' %',
                    width / 2 + xradio / 2 * Math.cos(current_angle),
                    height / 2 + yradio / 2 * Math.sin(current_angle) + 15);
        }

    };
}
