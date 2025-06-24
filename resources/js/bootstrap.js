import axios from 'axios';
import ru_RU from 'filepond/locale/ru-ru'
import * as FilePond from "filepond";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import 'filepond/dist/filepond.min.css'
import PhotoSwipeLightbox from "photoswipe/lightbox";
import PhotoSwipe from "photoswipe";
import 'photoswipe/dist/photoswipe.css';
import { Sortable } from "@shopify/draggable";

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.FilePond = FilePond;
FilePond.setOptions(ru_RU);
FilePond.registerPlugin(FilePondPluginImagePreview)

window.PhotoSwipeLightbox = PhotoSwipeLightbox;
window.PhotoSwipe = PhotoSwipe;

window.Sortable = Sortable;

