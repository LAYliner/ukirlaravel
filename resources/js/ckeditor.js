/**
 * This configuration was generated using the CKEditor 5 Builder. You can modify it anytime using this link:
 * https://ckeditor.com/ckeditor-5/builder/#installation/NoNgNARAzAdADDATBSB2VBGD6cE4CsccAHBgYvsYnBlHFMY6rsVM8pAKYB2KcYwDGCH8RwgLqRiAFmIgARgENFEcUA==
 */

import {
    ClassicEditor,
    AutoImage,
    Autoformat,
    Autosave,
    BalloonToolbar,
    ImageBlock,
    BlockQuote,
    Bold,
    CloudServices,
    Essentials,
    FontBackgroundColor,
    FontColor,
    FontFamily,
    FontSize,
    Heading,
    ImageCaption,
    ImageInsertViaUrl,
    ImageStyle,
    ImageTextAlternative,
    ImageToolbar,
    ImageUpload,
    ImageInline,
    Indent,
    IndentBlock,
    Italic,
    Link,
    LinkImage,
    List,
    MediaEmbed,
    Paragraph,
    Table,
    TableCaption,
    TableToolbar,
    TextTransformation,
    TodoList,
    Underline,
    SimpleUploadAdapter
} from 'ckeditor5';

import 'ckeditor5/ckeditor5.css';

//import './style.css';

/**
 * Create a free account with a trial: https://portal.ckeditor.com/checkout?plan=free
 */
const LICENSE_KEY = 'GPL'; // or <YOUR_LICENSE_KEY>.

const editorConfig = {
    root: {
        placeholder: 'Ketik atau paste konten Anda di sini!',
    },
    mediaEmbed: {
        previewsInData: true
    },
    toolbar: {
        items: [
            'undo',
            'redo',
            '|',
            'heading',
            '|',
            'fontSize',
            'fontFamily',
            'fontColor',
            'fontBackgroundColor',
            '|',
            'bold',
            'italic',
            'underline',
            '|',
            'link',
            'insertImage',
            'mediaEmbed',
            'insertTable',
            'blockQuote',
            '|',
            'bulletedList',
            'numberedList',
            'todoList',
            'outdent',
            'indent'
        ],
        shouldNotGroupWhenFull: false
    },
    plugins: [
        Autoformat,
        AutoImage,
        Autosave,
        BalloonToolbar,
        BlockQuote,
        Bold,
        CloudServices,
        Essentials,
        FontBackgroundColor,
        FontColor,
        FontFamily,
        FontSize,
        Heading,
        ImageBlock,
        ImageCaption,
        ImageInline,
        ImageInsertViaUrl,
        ImageStyle,
        ImageTextAlternative,
        ImageToolbar,
        ImageUpload,
        Indent,
        IndentBlock,
        Italic,
        Link,
        LinkImage,
        List,
        MediaEmbed,
        Paragraph,
        Table,
        TableCaption,
        TableToolbar,
        TextTransformation,
        TodoList,
        Underline,
        SimpleUploadAdapter
    ],
    licenseKey: LICENSE_KEY,
    balloonToolbar: ['bold', 'italic', '|', 'link', '|', 'bulletedList', 'numberedList'],
    fontFamily: {
        supportAllValues: true
    },
    fontSize: {
        options: [10, 12, 14, 'default', 18, 20, 22],
        supportAllValues: true
    },
    heading: {
        options: [
            {
                model: 'paragraph',
                title: 'Paragraph',
                class: 'ck-heading_paragraph'
            },
            {
                model: 'heading1',
                view: 'h1',
                title: 'Heading 1',
                class: 'ck-heading_heading1'
            },
            {
                model: 'heading2',
                view: 'h2',
                title: 'Heading 2',
                class: 'ck-heading_heading2'
            },
            {
                model: 'heading3',
                view: 'h3',
                title: 'Heading 3',
                class: 'ck-heading_heading3'
            },
            {
                model: 'heading4',
                view: 'h4',
                title: 'Heading 4',
                class: 'ck-heading_heading4'
            },
            {
                model: 'heading5',
                view: 'h5',
                title: 'Heading 5',
                class: 'ck-heading_heading5'
            },
            {
                model: 'heading6',
                view: 'h6',
                title: 'Heading 6',
                class: 'ck-heading_heading6'
            }
        ]
    },
    image: {
        toolbar: ['toggleImageCaption', 'imageTextAlternative', '|', 'imageStyle:inline', 'imageStyle:wrapText', 'imageStyle:breakText']
    },
    link: {
        addTargetToExternalLinks: true,
        defaultProtocol: 'https://',
        decorators: {
            toggleDownloadable: {
                mode: 'manual',
                label: 'Downloadable',
                attributes: {
                    download: 'file'
                }
            }
        }
    },
    table: {
        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
    },
    simpleUpload: {
        uploadUrl: '/admin/ckeditor/upload',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    }
};

const editorElement = document.querySelector('#editor');
if (editorElement) {
    ClassicEditor.create(editorElement, editorConfig)
        .then(editor => {
            window.editor = editor;
        })
        .catch(error => {
            console.error('CKEditor initialization error:', error);
        });
}
