import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['zone', 'input', 'fileList', 'fileCount', 'totalSize'];
    static values = {
        acceptImages: Boolean
    };

    connect() {
        this.files = [];
    }

    click(event) {
        // Ne pas appeler preventDefault pour laisser l'événement se propager normalement
        if (event.target !== this.inputTarget) {
            this.inputTarget.click();
        }
    }

    handleFileSelect(event) {
        this.processFiles(Array.from(event.target.files));
    }

    dragOver(event) {
        event.preventDefault();
        this.zoneTarget.classList.add('drag-over');
    }

    dragLeave(event) {
        event.preventDefault();
        this.zoneTarget.classList.remove('drag-over');
    }

    drop(event) {
        event.preventDefault();
        this.zoneTarget.classList.remove('drag-over');
        this.processFiles(Array.from(event.dataTransfer.files));
    }

    processFiles(newFiles) {
        newFiles.forEach(file => {
            // Validate image types if acceptImages is true
            if (this.acceptImagesValue && !file.type.startsWith('image/')) {
                alert('Seules les images sont acceptées !');
                return;
            }

            const fileId = Date.now() + Math.random();
            this.files.push({ id: fileId, file: file, progress: 0 });
            this.displayFile(fileId, file);
            this.simulateUpload(fileId);
        });

        this.updateStats();
    }

    displayFile(fileId, file) {
        const fileItem = document.createElement('div');
        fileItem.className = 'file-item';
        fileItem.id = `file-${fileId}`;

        const icon = this.getFileIcon(file.type);
        const size = this.formatFileSize(file.size);

        fileItem.innerHTML = `
            <div class="file-info">
                <div class="file-icon" id="icon-${fileId}">${icon}</div>
                <div class="file-details">
                    <div class="file-name">${file.name}</div>
                    <div class="file-size">${size}</div>
                    <div class="file-progress">
                        <div class="file-progress-bar" style="width: 0%"></div>
                    </div>
                </div>
            </div>
            <div class="file-status">
                <span class="status-badge uploading">Upload...</span>
                <button class="btn-remove" data-action="click->dropzone#removeFile" data-file-id="${fileId}">×</button>
            </div>
        `;

        this.fileListTarget.appendChild(fileItem);

        // Add image preview if it's an image
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const iconElement = document.getElementById(`icon-${fileId}`);
                if (iconElement) {
                    iconElement.innerHTML = `<img src="${e.target.result}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">`;
                }
            };
            reader.readAsDataURL(file);
        }
    }

    simulateUpload(fileId) {
        const fileItem = document.getElementById(`file-${fileId}`);
        if (!fileItem) return;

        const progressBar = fileItem.querySelector('.file-progress-bar');
        const statusBadge = fileItem.querySelector('.status-badge');

        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 30;
            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);
                statusBadge.textContent = '✓ Terminé';
                statusBadge.className = 'status-badge success';
            }
            progressBar.style.width = progress + '%';
        }, 300);
    }

    removeFile(event) {
        event.stopPropagation();
        const button = event.currentTarget;
        const fileId = button.dataset.fileId;
        const fileItem = document.getElementById(`file-${fileId}`);

        if (fileItem) {
            fileItem.remove();
            const index = this.files.findIndex(f => f.id == fileId);
            if (index > -1) this.files.splice(index, 1);
            this.updateStats();
        }
    }

    updateStats() {
        if (this.hasFileCountTarget) {
            this.fileCountTarget.textContent = this.files.length;
        }

        if (this.hasTotalSizeTarget) {
            const total = this.files.reduce((sum, f) => sum + f.file.size, 0);
            this.totalSizeTarget.textContent = this.formatFileSize(total);
        }
    }

    getFileIcon(type) {
        if (type.startsWith('image/')) return '🖼️';
        if (type.includes('pdf')) return '📄';
        if (type.includes('word')) return '📝';
        if (type.includes('excel')) return '📊';
        if (type.includes('zip')) return '🗜️';
        return '📁';
    }

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }
}
