<div>
    @extends('layouts.app')

    @section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">
                            <i class="fas fa-upload me-2"></i>Upload New Image
                        </h3>
                        <button wire:click="cancel" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back to Gallery
                        </button>
                    </div>

                    <div class="card-body">
                        @if (session()->has('message'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle me-2"></i>{{ session('message') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form wire:submit.prevent="save">
                            <div class="row">
                                <!-- File Upload Section -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="image" class="form-label">
                                            <i class="fas fa-image me-2"></i>Select Image <span class="text-danger">*</span>
                                        </label>
                                        <input type="file"
                                               wire:model="image"
                                               id="image"
                                               class="form-control @error('image') is-invalid @enderror"
                                               accept="image/*">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Supported formats: JPG, JPEG, PNG, GIF. Max size: 10MB
                                        </div>
                                    </div>

                                    <!-- Image Preview -->
                                    @if ($preview_image)
                                        <div class="mb-3">
                                            <label class="form-label">
                                                <i class="fas fa-eye me-2"></i>Preview
                                            </label>
                                            <div class="border rounded p-2 text-center">
                                                <img src="{{ $preview_image }}"
                                                     alt="Preview"
                                                     class="img-fluid rounded"
                                                     style="max-height: 200px;">
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Form Fields Section -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">
                                            <i class="fas fa-tag me-2"></i>Title <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               wire:model="title"
                                               id="title"
                                               class="form-control @error('title') is-invalid @enderror"
                                               placeholder="Enter image title">
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="category" class="form-label">
                                            <i class="fas fa-folder me-2"></i>Category <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               wire:model="category"
                                               id="category"
                                               class="form-control @error('category') is-invalid @enderror"
                                               placeholder="Enter category">
                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">
                                            <i class="fas fa-align-left me-2"></i>Description
                                        </label>
                                        <textarea wire:model="description"
                                                  id="description"
                                                  class="form-control @error('description') is-invalid @enderror"
                                                  rows="4"
                                                  placeholder="Enter description (optional)"></textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between">
                                <button type="button" wire:click="cancel" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>

                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                    <span wire:loading.remove>
                                        <i class="fas fa-upload me-2"></i>Upload Image
                                    </span>
                                    <span wire:loading>
                                        <i class="fas fa-spinner fa-spin me-2"></i>Uploading...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('scripts')
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
    @endpush
</div>
