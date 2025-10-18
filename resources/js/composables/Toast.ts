import Swal from 'sweetalert2'
import { router } from '@inertiajs/vue3'

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  iconColor: 'white',
  customClass: {
    popup: 'colored-toast',
  },
  showConfirmButton: false,
  timer: 1500,
  timerProgressBar: true,
})

const getErrorMessage = (error: string) => {
  Toast.fire({
    icon: 'error',
    title: error,
})
}

const getSuccessMessage = (message: string) => {
  Toast.fire({
    icon: 'success',
    title: message,
  })
}

const questionDeleteMessage = (route: any,  text: string, model: string) => {
   Swal.fire({
      title: '¿Estas Seguro?',
      text: text,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Eliminar!',
    }).then((result) => {
      if (result.isConfirmed) {
        try {
          router.delete(route);
          getSuccessMessage(`${model} Eliminado Correctamente`)
        } catch (error) {
          getErrorMessage(`No podemos Eliminar Este ${model}!`)
        }
      } else {
        return
      }
    })
}

export { getErrorMessage, getSuccessMessage, questionDeleteMessage }
