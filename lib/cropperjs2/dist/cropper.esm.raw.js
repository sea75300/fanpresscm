/*! Cropper.js v2.1.0 | (c) 2015-present Chen Fengyuan | MIT */
import { isString, isElement, getRootDocument, CROPPER_IMAGE, CROPPER_CANVAS, CROPPER_SELECTION } from '@cropper/utils';
export * from '@cropper/utils';
import { CropperCanvas, CropperCrosshair, CropperGrid, CropperHandle, CropperImage, CropperSelection, CropperShade, CropperViewer } from '@cropper/elements';
export * from '@cropper/elements';

var DEFAULT_TEMPLATE = ('<cropper-canvas background>'
    + '<cropper-image rotatable scalable skewable translatable></cropper-image>'
    + '<cropper-shade hidden></cropper-shade>'
    + '<cropper-handle action="select" plain></cropper-handle>'
    + '<cropper-selection initial-coverage="0.5" movable resizable>'
    + '<cropper-grid role="grid" bordered covered></cropper-grid>'
    + '<cropper-crosshair centered></cropper-crosshair>'
    + '<cropper-handle action="move" theme-color="rgba(255, 255, 255, 0.35)"></cropper-handle>'
    + '<cropper-handle action="n-resize"></cropper-handle>'
    + '<cropper-handle action="e-resize"></cropper-handle>'
    + '<cropper-handle action="s-resize"></cropper-handle>'
    + '<cropper-handle action="w-resize"></cropper-handle>'
    + '<cropper-handle action="ne-resize"></cropper-handle>'
    + '<cropper-handle action="nw-resize"></cropper-handle>'
    + '<cropper-handle action="se-resize"></cropper-handle>'
    + '<cropper-handle action="sw-resize"></cropper-handle>'
    + '</cropper-selection>'
    + '</cropper-canvas>');

const REGEXP_ALLOWED_ELEMENTS = /^img|canvas$/;
const REGEXP_BLOCKED_TAGS = /<(\/?(?:script|style)[^>]*)>/gi;
const DEFAULT_OPTIONS = {
    template: DEFAULT_TEMPLATE,
};
CropperCanvas.$define();
CropperCrosshair.$define();
CropperGrid.$define();
CropperHandle.$define();
CropperImage.$define();
CropperSelection.$define();
CropperShade.$define();
CropperViewer.$define();
class Cropper {
    constructor(element, options) {
        var _a;
        this.options = DEFAULT_OPTIONS;
        if (isString(element)) {
            element = document.querySelector(element);
        }
        if (!isElement(element) || !REGEXP_ALLOWED_ELEMENTS.test(element.localName)) {
            throw new Error('The first argument is required and must be an <img> or <canvas> element.');
        }
        this.element = element;
        options = Object.assign(Object.assign({}, DEFAULT_OPTIONS), options);
        this.options = options;
        let { container } = options;
        if (container) {
            if (isString(container)) {
                container = (_a = getRootDocument(element)) === null || _a === void 0 ? void 0 : _a.querySelector(container);
            }
            if (!isElement(container)) {
                throw new Error('The `container` option must be an element or a valid selector.');
            }
        }
        if (!isElement(container)) {
            if (element.parentElement) {
                container = element.parentElement;
            }
            else {
                container = element.ownerDocument.body;
            }
        }
        this.container = container;
        const tagName = element.localName;
        let src = '';
        if (tagName === 'img') {
            ({ src } = element);
        }
        else if (tagName === 'canvas' && window.HTMLCanvasElement) {
            src = element.toDataURL();
        }
        const { template } = options;
        if (template && isString(template)) {
            const templateElement = document.createElement('template');
            const documentFragment = document.createDocumentFragment();
            templateElement.innerHTML = template.replace(REGEXP_BLOCKED_TAGS, '&lt;$1&gt;');
            documentFragment.appendChild(templateElement.content);
            Array.from(documentFragment.querySelectorAll(CROPPER_IMAGE)).forEach((image) => {
                image.setAttribute('src', src);
                image.setAttribute('alt', element.alt || 'The image to crop');
                // Inherit additional attributes from HTMLImageElement
                if (tagName === 'img') {
                    [
                        'crossorigin',
                        'decoding',
                        'elementtiming',
                        'fetchpriority',
                        'loading',
                        'referrerpolicy',
                        'sizes',
                        'srcset',
                    ].forEach((attribute) => {
                        if (element.hasAttribute(attribute)) {
                            image.setAttribute(attribute, element.getAttribute(attribute) || '');
                        }
                    });
                }
            });
            if (element.parentElement) {
                element.style.display = 'none';
                container.insertBefore(documentFragment, element.nextSibling);
            }
            else {
                container.appendChild(documentFragment);
            }
        }
    }
    getCropperCanvas() {
        return this.container.querySelector(CROPPER_CANVAS);
    }
    getCropperImage() {
        return this.container.querySelector(CROPPER_IMAGE);
    }
    getCropperSelection() {
        return this.container.querySelector(CROPPER_SELECTION);
    }
    getCropperSelections() {
        return this.container.querySelectorAll(CROPPER_SELECTION);
    }
    destroy() {
        var _a;
        const cropperCanvas = this.getCropperCanvas();
        if (cropperCanvas) {
            (_a = cropperCanvas.parentElement) === null || _a === void 0 ? void 0 : _a.removeChild(cropperCanvas);
        }
        if (this.element) {
            this.element.style.display = '';
        }
    }
}
Cropper.version = '2.1.0';

export { DEFAULT_TEMPLATE, Cropper as default };
