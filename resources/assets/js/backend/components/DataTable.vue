<template>
    <div class="table-container">
        <table class="table table-striped table-bordered table-hover"
               cellspacing="0"
               width="100%"></table>
        <batch-action v-if="actions !== null" :options="actions" @action="onBulkAction"></batch-action>
    </div>
</template>

<script>
  import axios from 'axios'

  export default {
    props: {
      options: {
        type: Object,
        default: null
      },
      actions: {
        type: Object,
        default: null
      },
      actionRouteName: {
        type: String,
        default: null
      }
    },
    methods: {
      onBulkAction ($name) {
        let dataTable = $(this.$el).find('table').DataTable()
        axios.post(this.$app.route(this.actionRouteName), {
          action: $name,
          ids: dataTable.rows({selected: true}).ids().toArray()
        }).then(response => {
          // Reload Datatables and keep current pager
          dataTable.ajax.reload(null, false)
          window.toastr[response.data.status](response.data.message)
        }).catch(error => {
          // Not allowed error
          if (error.response.status === 403) {
            window.toastr.error(this.$i18n.t('exceptions.unauthorized'))
            return
          }

          // Domain error
          if (error.response.data.message !== undefined) {
            window.toastr.error(error.response.data.message)
            return
          }

          // Generic error
          window.toastr.error(this.$i18n.t('exceptions.general'))
        })
      }
    },
    mounted () {
      let options = this.options
      let $container = $(this.$el)
      let $table = $container.find('table')
      let $formAction = $container.find('form')

      /**
       * Fix remove form-inline
       */
      $.extend($.fn.dataTable.ext.classes, {
        sWrapper: 'dataTables_wrapper dt-bootstrap4'
      })
      /**
       * Default options
       */
      let dataTableOptions = {
        lengthMenu: [[5, 10, 15, 25, 50, -1], [5, 10, 15, 25, 50, window.locale === 'en' ? 'All' : 'Tout']],
        buttons: [
          {
            text: this.$i18n.t('labels.export'),
            extend: 'csvHtml5'
          }
        ],
        dom:
        '<\'row\'<\'col-md-4\'l><\'col-md-4 text-center\'i><\'col-md-4\'f>>' +
        '<\'row\'<\'col\'tr>>' +
        '<\'row\'<\'col-md-4 table-group-actions\'><\'col-md-4\'p><\'col-md-4 text-right\'B>>',
        ajax: {
          error: (xhr) => {
            // If not logged, force redirect
            if (xhr.status === 401) {
              window.location = this.$app.route('admin.login')
              return
            }

            // Not allowed error
            if (xhr.status === 403) {
              window.toastr.error(this.$i18n.t('exceptions.unauthorized'))
              return
            }

            // Generic error
            window.toastr.error(this.$i18n.t('exceptions.general'))
          }
        }
      }

      if (window.locale !== 'en') {
        dataTableOptions['language'] = {
          url: `/i18n/datatables.${window.locale}.json`
        }
      }

      $.extend(true, $.fn.dataTable.defaults, dataTableOptions)
      $.fn.dataTable.ext.errMode = 'none'

      /**
       * Integrate form actions into datatable layout
       */
      $(document).on('preInit.dt', () => {
        let $actionWrapper = $container.find('.table-group-actions')
        $formAction.detach().appendTo($actionWrapper)
      })

      /**
       * Delete actions from AJAX
       */
      $(document).ajaxComplete(() => {
        $('[data-toggle="delete-row"]').click((e) => {
          e.preventDefault()
          let url = $(e.currentTarget).attr('href')
          let dataTable = $(e.currentTarget).closest('table').DataTable()

          $.confirmSwal(e.currentTarget, () => {
            axios.delete(url)
              .then(response => {
                // Reload Datatables and keep current pager
                dataTable.ajax.reload(null, false)
                window.toastr[response.data.status](response.data.message)
              })
              .catch(error => {
                // Not allowed error
                if (error.response.status === 403) {
                  window.toastr.error(this.$i18n.t('exceptions.unauthorized'))
                  return
                }

                // Domain error
                if (error.response.data.error !== undefined) {
                  window.toastr.error(error.response.data.error)
                  return
                }

                // Generic error
                window.toastr.error(this.$i18n.t('exceptions.general'))
              })
          })
        })
      })

      $table.DataTable(options)
    }
  }
</script>
