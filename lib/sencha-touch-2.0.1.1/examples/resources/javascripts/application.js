window.onload = function() {
    var request = new XMLHttpRequest(),
        url = "examples.json",
        section = document.getElementsByTagName('section')[0],
        categories = [],
        retina = window.devicePixelRatio > 1 ? true : false,
        category, item, ul, element, ln, i, j;

    request.open('GET', url, false);
    request.send(null);

    if (request.status === 200) {
        categories = JSON.parse(request.responseText);

        ln = categories.length;
        for (i = 0; i < ln; i++) {
            category = categories[i];

            element = document.createElement('header');
            element.innerHTML = category.title;
            section.appendChild(element);

            ul = document.createElement('ul');

            for (j = 0; j < category.items.length; j++) {
                item = category.items[j];

                element = document.createElement('li');
                element.innerHTML = [
                    '<a href="' + item.url + '">',
                        '<img src="' + ((retina && item.icon2x) ? item.icon2x : item.icon) + '" />',
                        '<h3>' + item.text + '</h3>',
                        '<p>' + item.desc + '</p>',
                    '</a>'
                ].join('');
                ul.appendChild(element);
            }

            section.appendChild(ul);
        }

        document.getElementById('wrapper').style.opacity = 1;
    }
};
