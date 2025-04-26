document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.device-button').forEach((button) => {
        button.addEventListener('click', () => {
            const buttonId = button.getAttribute('data-id');

            const modalBackground = document.createElement('div');
            modalBackground.classList.add('modal-background');
            document.body.appendChild(modalBackground);

            const modal = document.createElement('div');
            modal.classList.add('modal');

            const modalContent = document.createElement('div');
            modalContent.classList.add('modal-content');

            const modalHeader = document.createElement('h2');
            modalHeader.textContent = `Device ID: ${buttonId}`;

            const disableTrackModalButton = document.createElement('button');
            disableTrackModalButton.classList.add('disable-track-button');
            disableTrackModalButton.textContent = 'Disable Tracking';

            const closeModalButton = document.createElement('button');
            closeModalButton.classList.add('close-modal');
            closeModalButton.textContent = 'Close';

            disableTrackModalButton.addEventListener('click', async () => {
                const formData = new FormData();
                formData.append('delete_device', '1');
                formData.append('device_id', buttonId);

                const response = await fetch('changeTracking.php', {
                    method: 'POST',
                    body: formData
                });

                if (await response) {
                    location.reload();
                }
            });

            // Add event listener to close the modal
            closeModalButton.addEventListener('click', () => {
                document.body.removeChild(modalBackground);
            });

            // Add animation to modal when modalBackground is clicked
            modalBackground.addEventListener('click', (event) => {
                if (event.target === modalBackground) {
                    modal.classList.add('animate-modal');
                    setTimeout(() => {
                        modal.classList.remove('animate-modal');
                    }, 300);
                }
            });

            // Append elements to modal content
            modalContent.appendChild(modalHeader);
            modalContent.appendChild(disableTrackModalButton);
            modalContent.appendChild(closeModalButton);

            // Append modal content to modal
            modal.appendChild(modalContent);

            modalBackground.appendChild(modal);
        });
    });

    document.getElementById("addButton").addEventListener("click", () => {
        const modalBackground = document.createElement('div');
        modalBackground.classList.add('modal-background');
        document.body.appendChild(modalBackground);

        const modal = document.createElement('div');
        modal.classList.add('modal');

        const modalContent = document.createElement('div');
        modalContent.classList.add('modal-content');

        const modalHeader = document.createElement('h2');
        modalHeader.textContent = 'Add Device';

        const ipInput = document.createElement('input');
        ipInput.type = 'text';
        ipInput.placeholder = 'Enter IP Address';
        ipInput.classList.add('ip-input');

        const addDeviceButton = document.createElement('button');
        addDeviceButton.classList.add('add-device-button');
        addDeviceButton.textContent = 'Add Device';

        const closeModalButton = document.createElement('button');
        closeModalButton.classList.add('close-modal');
        closeModalButton.textContent = 'Close';

        // Add event listener to close the modal
        closeModalButton.addEventListener('click', () => {
            document.body.removeChild(modalBackground);
        });

        // Add animation to modal when modalBackground is clicked
        modalBackground.addEventListener('click', (event) => {
            if (event.target === modalBackground) {
                modal.classList.add('animate-modal');
                setTimeout(() => {
                    modal.classList.remove('animate-modal');
                }, 300);
            }
        });

        // Add event listener to add device button
        addDeviceButton.addEventListener('click', async () => {
            const ipValue = ipInput.value.trim();
            const ipRegex = /^(25[0-5]|2[0-4][0-9]|[0-1]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[0-1]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[0-1]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[0-1]?[0-9][0-9]?)$/;

            if (!ipRegex.test(ipValue)) {
                alert('Please enter a valid IP address.');
                return;
            }
            const existingCards = document.querySelectorAll('.device > p:first-of-type');
            for (const card of existingCards) {
                if (card.textContent.includes(ipValue)) {
                    alert('This IP address is already being tracked.');
                    return;
                }
            }

            const formData = new FormData();
            formData.append('add_device', '1');
            formData.append('device_ip', ipValue);

            const response = await fetch('/public/changeTracking.php', {
                method: 'POST',
                body: formData
            });

            const responseText = await response.text();
            console.log(responseText);

            responseText != "null" ? location.reload() : alert('Device not finded!');
        });

        // Append elements to modal content
        modalContent.appendChild(modalHeader);
        modalContent.appendChild(ipInput);
        modalContent.appendChild(addDeviceButton);
        modalContent.appendChild(closeModalButton);

        // Append modal content to modal
        modal.appendChild(modalContent);

        // Append the new modal to the background
        modalBackground.appendChild(modal);

        ipInput.focus();
    });
});