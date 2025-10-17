import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['input', 'canvas', 'preview', 'info', 'dimensions', 'applyBtn'];

    connect() {
        this.currentImage = null;
        this.currentRatio = 'free';
        this.rotation = 0;
        this.flipH = false;
        this.flipV = false;
    }

    triggerFileInput() {
        this.inputTarget.click();
    }

    handleFileSelect(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.currentImage = new Image();
                this.currentImage.onload = () => {
                    this.displayImage();
                };
                this.currentImage.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    displayImage() {
        this.canvasTarget.innerHTML = '';
        const img = document.createElement('img');
        img.src = this.currentImage.src;
        img.style.transform = `rotate(${this.rotation}deg) scaleX(${this.flipH ? -1 : 1}) scaleY(${this.flipV ? -1 : 1})`;
        this.canvasTarget.appendChild(img);

        // Create crop overlay
        this.createCropOverlay();
        if (this.hasApplyBtnTarget) {
            this.applyBtnTarget.disabled = false;
        }
        this.updatePreview();
    }

    createCropOverlay() {
        const overlay = document.createElement('div');
        overlay.className = 'crop-area';
        overlay.style.cssText = 'left: 10%; top: 10%; width: 80%; height: 80%;';
        this.cropArea = overlay;

        // Add corner handles
        ['nw', 'ne', 'sw', 'se'].forEach(pos => {
            const handle = document.createElement('div');
            handle.className = `crop-handle ${pos}`;
            handle.dataset.position = pos;
            overlay.appendChild(handle);
        });

        this.canvasTarget.appendChild(overlay);

        // Make the crop area draggable
        this.makeDraggable(overlay);

        // Make handles resizable
        overlay.querySelectorAll('.crop-handle').forEach(handle => {
            this.makeResizable(handle);
        });
    }

    makeDraggable(element) {
        let isDragging = false;
        let startX, startY, startLeft, startTop;

        const onMouseDown = (e) => {
            // Only drag if clicking on the overlay itself, not the handles
            if (e.target.classList.contains('crop-handle')) return;

            isDragging = true;
            startX = e.clientX;
            startY = e.clientY;
            startLeft = parseFloat(element.style.left) || 0;
            startTop = parseFloat(element.style.top) || 0;

            e.preventDefault();
        };

        const onMouseMove = (e) => {
            if (!isDragging) return;

            const containerRect = this.canvasTarget.getBoundingClientRect();
            const dx = ((e.clientX - startX) / containerRect.width) * 100;
            const dy = ((e.clientY - startY) / containerRect.height) * 100;

            let newLeft = startLeft + dx;
            let newTop = startTop + dy;

            // Constrain within bounds
            const width = parseFloat(element.style.width) || 80;
            const height = parseFloat(element.style.height) || 80;

            newLeft = Math.max(0, Math.min(newLeft, 100 - width));
            newTop = Math.max(0, Math.min(newTop, 100 - height));

            element.style.left = newLeft + '%';
            element.style.top = newTop + '%';
        };

        const onMouseUp = () => {
            isDragging = false;
        };

        element.addEventListener('mousedown', onMouseDown);
        document.addEventListener('mousemove', onMouseMove);
        document.addEventListener('mouseup', onMouseUp);
    }

    makeResizable(handle) {
        let isResizing = false;
        let startX, startY, startWidth, startHeight, startLeft, startTop;
        const position = handle.dataset.position;

        const onMouseDown = (e) => {
            isResizing = true;
            startX = e.clientX;
            startY = e.clientY;

            const overlay = this.cropArea;
            startWidth = parseFloat(overlay.style.width) || 80;
            startHeight = parseFloat(overlay.style.height) || 80;
            startLeft = parseFloat(overlay.style.left) || 10;
            startTop = parseFloat(overlay.style.top) || 10;

            e.preventDefault();
            e.stopPropagation();
        };

        const onMouseMove = (e) => {
            if (!isResizing) return;

            const containerRect = this.canvasTarget.getBoundingClientRect();
            const dx = ((e.clientX - startX) / containerRect.width) * 100;
            const dy = ((e.clientY - startY) / containerRect.height) * 100;

            const overlay = this.cropArea;
            let newWidth = startWidth;
            let newHeight = startHeight;
            let newLeft = startLeft;
            let newTop = startTop;

            // Adjust based on which handle is being dragged
            if (position.includes('e')) {
                newWidth = Math.max(10, Math.min(startWidth + dx, 100 - startLeft));
            }
            if (position.includes('w')) {
                newWidth = Math.max(10, startWidth - dx);
                newLeft = Math.max(0, Math.min(startLeft + dx, startLeft + startWidth - 10));
            }
            if (position.includes('s')) {
                newHeight = Math.max(10, Math.min(startHeight + dy, 100 - startTop));
            }
            if (position.includes('n')) {
                newHeight = Math.max(10, startHeight - dy);
                newTop = Math.max(0, Math.min(startTop + dy, startTop + startHeight - 10));
            }

            overlay.style.width = newWidth + '%';
            overlay.style.height = newHeight + '%';
            overlay.style.left = newLeft + '%';
            overlay.style.top = newTop + '%';
        };

        const onMouseUp = () => {
            isResizing = false;
        };

        handle.addEventListener('mousedown', onMouseDown);
        document.addEventListener('mousemove', onMouseMove);
        document.addEventListener('mouseup', onMouseUp);
    }

    setRatio(event) {
        // Remove active class from all buttons
        const buttons = this.element.querySelectorAll('.aspect-ratio-btn');
        buttons.forEach(btn => btn.classList.remove('active'));

        // Add active class to clicked button
        event.currentTarget.classList.add('active');

        // Set the ratio
        this.currentRatio = event.currentTarget.dataset.ratio;
        this.updatePreview();
    }

    rotateLeft() {
        this.rotation -= 90;
        if (this.currentImage) this.displayImage();
    }

    rotateRight() {
        this.rotation += 90;
        if (this.currentImage) this.displayImage();
    }

    flipHorizontal() {
        this.flipH = !this.flipH;
        if (this.currentImage) this.displayImage();
    }

    flipVertical() {
        this.flipV = !this.flipV;
        if (this.currentImage) this.displayImage();
    }

    reset() {
        this.rotation = 0;
        this.flipH = false;
        this.flipV = false;
        this.currentRatio = 'free';

        const buttons = this.element.querySelectorAll('.aspect-ratio-btn');
        buttons.forEach(btn => btn.classList.remove('active'));
        this.element.querySelector('.aspect-ratio-btn[data-ratio="free"]').classList.add('active');

        if (this.currentImage) this.displayImage();
    }

    applyCrop() {
        if (!this.currentImage) return;

        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        // Calculate dimensions based on current ratio
        let width, height;
        if (this.currentRatio === '1:1') {
            width = height = 400;
        } else if (this.currentRatio === '16:9') {
            width = 640;
            height = 360;
        } else if (this.currentRatio === '4:3') {
            width = 640;
            height = 480;
        } else {
            width = 640;
            height = 480;
        }

        canvas.width = width;
        canvas.height = height;

        ctx.save();
        ctx.translate(width / 2, height / 2);
        ctx.rotate(this.rotation * Math.PI / 180);
        ctx.scale(this.flipH ? -1 : 1, this.flipV ? -1 : 1);
        ctx.drawImage(this.currentImage, -width / 2, -height / 2, width, height);
        ctx.restore();

        // Show result in preview
        const resultImg = new Image();
        resultImg.src = canvas.toDataURL();
        resultImg.className = 'preview-image';
        this.previewTarget.innerHTML = '';
        this.previewTarget.appendChild(resultImg);

        if (this.hasInfoTarget) {
            this.infoTarget.style.display = 'block';
        }
        if (this.hasDimensionsTarget) {
            this.dimensionsTarget.textContent = `${width}×${height}px`;
        }

        alert(`Image recadrée avec succès ! Dimensions: ${width}×${height}px`);
    }

    updatePreview() {
        if (!this.currentImage || !this.hasPreviewTarget) return;

        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        let width, height;
        if (this.currentRatio === '1:1') {
            width = height = 200;
        } else if (this.currentRatio === '16:9') {
            width = 200;
            height = 112;
        } else if (this.currentRatio === '4:3') {
            width = 200;
            height = 150;
        } else {
            width = 200;
            height = 150;
        }

        canvas.width = width;
        canvas.height = height;

        ctx.drawImage(this.currentImage, 0, 0, width, height);

        const previewImg = new Image();
        previewImg.src = canvas.toDataURL();
        previewImg.className = 'preview-image';
        this.previewTarget.innerHTML = '';
        this.previewTarget.appendChild(previewImg);
    }
}
