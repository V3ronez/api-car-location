<template>
    <div class="modal fade" :id="id" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <slot name="alert"></slot>
                    <slot name="content-body"></slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer-modal"></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['id', 'title']
}
</script>
