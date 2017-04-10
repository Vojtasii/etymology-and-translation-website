function changeUrl(action, element, value) { //URI.js is required
    var uri = new URI(location.href);
    switch (action) {
        case "add":
            //checks query for an element with the same name and value
            //if it exists it calls itself with the value + 1
            //otherwise adds the parameter
            if (uri.hasQuery(element, value, true)) {changeUrl("add", element, parseInt(value) + 1); return;}
            if (uri.hasQuery(element, false)) {uri.addQuery(element, value); value = parseInt(value) + 1;}
            uri.addQuery(element, value);
            break;
        case "rem":
            //loads the query as an object, selects just one element
            //checks for emptiness (removes immediately if empty)
            //checks if the value is an array (if it is, selects just the last value of the array)
            //removes the parameter
            var query = uri.query(true);
            query = query[element];
            if (query == null) {uri.removeQuery(element); break;}
            if (Array.isArray(query)) {query = query[query.length-1]};
            uri.removeQuery(element, query);
    }
    uri.removeQuery("sent");
    location.href = uri;
}

function setParameter(element, index) {
    //loads the query as an object, selects just one element
    //checks for emptiness (sets the value immediately if empty)
    //checks if the value is an array (if it is, changes just the desired value in query)
    //  checks query for an element with the same name and value
    //  if it exists, it swaps the values
    //sets the parameter
    var uri = new URI(location.href);
    var query = uri.query(true);
    query = query[element];
    if (query === null) {uri.setQuery(element, document.getElementsByName(element)[index].value);}
    else {
        if (typeof query === "object") {
            var temp = document.getElementsByName(element)[index].value;
            if (uri.hasQuery(element, temp, true)) {
                query[query.indexOf(temp)] = query[index];
            }
        query[index] = temp;
        }
        else {query = document.getElementsByName(element)[index].value;}
        uri.setQuery(element, query);
    }
    uri.removeQuery("sent");
    location.href = uri;
}

function saveValue(element, value) {
    var uri = new URI(location.href);
    uri.setQuery(element, value);
    uri.removeQuery("sent");
    history.pushState("saveInUrl","",uri);
}

function resetUrl() {
    var uri = new URI(location.href);
    uri.query("");
    location.href = uri;
}

