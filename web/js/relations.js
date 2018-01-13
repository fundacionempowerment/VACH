var width = 500;
var height = 350;
var imageSize = 30;
var margin = 40;
var arrow_x_ring = 50;
var text_height = 6;
// This code asume square proto human image
var arrow_y_ring = arrow_x_ring * height / width;
var xradio = width / 2 - imageSize / 2 - margin;
var yradio = xradio * height / width;
var image_text_height = imageSize + text_height;
var path = document.location.pathname;
path = path.substr(0, path.lastIndexOf("index.php"));

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

function drawPersons(context, data, imageObj, who) {
    if (data.length < 4)
        height = 150;

    for (i in data) {
        if (i > 0)
            var current_angle = (i - 1) * 2 * Math.PI / (data.length - 1);
        // draw member image
        context.drawImage(imageObj,
                width / 2 + xradio * Math.cos(current_angle) - imageSize / 2,
                height / 2 + yradio * Math.sin(current_angle) - image_text_height / 2);

        context.textBaseline = 'top';
        context.textAlign = 'center';
        context.fillText(data[i][who + '_name'] + ' ' + data[i][who + '_surname'],
                width / 2 + xradio * Math.cos(current_angle),
                height / 2 + yradio * Math.sin(current_angle) + image_text_height / 2 - 2);

        context.fillText(Math.round(data[i]['value'] * 1000 / 4) / 10 + ' %',
                width / 2 + xradio / 2 * Math.cos(current_angle),
                height / 2 + yradio / 2 * Math.sin(current_angle) + 5);
    }

    // draw central image
    context.drawImage(imageObj, (width - imageSize) / 2, (height - image_text_height) / 2);
    context.textBaseline = 'top';
    context.textAlign = 'center';
    context.fillText(data[0][who + '_name'] + ' ' + data[0][who + '_surname'],
            width / 2,
            height / 2 + image_text_height / 2);
}

function doForwardRelations(context, data)
{
    var imageObj = new Image();
    imageObj.src = path + 'images/protoMale.png';
    imageObj.onload = function () {
        context.font = '11pt Helvetica';

        for (i in data) {
            if (i > 0)
                var current_angle = (i - 1) * 2 * Math.PI / (data.length - 1);
            // draw member image
            if (data[i]['value'] < 4 / 3)
                context.strokeStyle = '#d9534f';
            else if (data[i]['value'] > 4 * 2 / 3)
                context.strokeStyle = '#5cb85c';
            else
                context.strokeStyle = '#f0ad4e';
            canvas_arrow(context,
                    width / 2 + (xradio - arrow_x_ring) * Math.cos(current_angle),
                    height / 2 + (yradio - arrow_y_ring) * Math.sin(current_angle),
                    width / 2 + arrow_x_ring * Math.cos(current_angle),
                    height / 2 + arrow_y_ring * Math.sin(current_angle));
        }

        drawPersons(context, data, imageObj, 'observer');
    };
}

function doBackwardRelations(context, data)
{
    var imageObj = new Image();
    imageObj.src = path + 'images/protoMale.png';
    imageObj.onload = function () {
        context.font = '11pt Helvetica';

        for (i in data) {
            if (i > 0)
                var current_angle = (i - 1) * 2 * Math.PI / (data.length - 1);
            // draw member image
            if (data[i]['value'] < 4 / 3)
                context.strokeStyle = '#d9534f';
            else if (data[i]['value'] > 4 * 2 / 3)
                context.strokeStyle = '#5cb85c';
            else
                context.strokeStyle = '#f0ad4e';
            canvas_arrow(context,
                    width / 2 + arrow_x_ring * Math.cos(current_angle),
                    height / 2 + arrow_y_ring * Math.sin(current_angle),
                    width / 2 + (xradio - arrow_x_ring) * Math.cos(current_angle),
                    height / 2 + (yradio - arrow_y_ring) * Math.sin(current_angle)
                    );
        }

        drawPersons(context, data, imageObj, 'observed');
    };
}
