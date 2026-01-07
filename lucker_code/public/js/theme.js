if (localStorage.getItem("style") == "dark") {
    document.querySelector(".theme").href = "/assets/dark.css?v=" + Date.now();
}
function changeTheme() {
    if (document.querySelector(".theme").getAttribute("href") === "#") {
        document.querySelector(".theme").href = "/assets/dark.css?v=" + Date.now();
        localStorage.setItem("style", "dark");
    } else {
        document.querySelector(".theme").href = "#";
        localStorage.setItem("style", "light");
    }
}
