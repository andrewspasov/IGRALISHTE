export function previewProfilePhoto() {
    var reader = new FileReader();
    reader.onload = function (e) {
        document.getElementById("profilePhotoPreview").src = e.target.result;
    };
    reader.readAsDataURL(document.getElementById("profile_photo").files[0]);
}

document.addEventListener("DOMContentLoaded", function () {
    const phoneNumberInput = document.getElementById("phone_number");

    phoneNumberInput.addEventListener("input", formatPhoneNumber);

    function formatPhoneNumber() {
        let inputNumbersValue = this.value.replace(/[^\d+]/g, "");

        if (inputNumbersValue.startsWith("+")) {
            inputNumbersValue =
                "+" +
                inputNumbersValue.substr(1).replace(/(\d{3})(?=\d)/g, "$1 ");
        } else {
            inputNumbersValue = inputNumbersValue.replace(
                /(\d{3})(?=\d)/g,
                "$1 "
            );
        }

        this.value = inputNumbersValue;
    }
});
