
var fileState = {
    used: false
}

var textState = {
    used: false
}

function changeFileForm()
{
    if (fileState.used)
    {
        return;
    }
    if (textState.used)
    {
        document.getElementById("textMenu").style.display = "none";
        document.getElementById("textButton").classList.remove("button_used");
        textState.used = false;
    }
    fileState.used = true;
    var elem = document.getElementById("fileMenu").style.display = "flex";
    document.getElementById("fileButton").classList.add("button_used");
}

function changeTextForm()
{
    if (textState.used)
    {
        return;
    }
    if (fileState.used)
    {
        document.getElementById("fileMenu").style.display = "none";
        document.getElementById("fileButton").classList.remove("button_used");
        fileState.used = false;
    }
    textState.used = true;
    var elem = document.getElementById("textMenu").style.display = "flex";
    document.getElementById("textButton").classList.add("button_used");
}

