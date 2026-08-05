import { enqueueSnackbar } from 'notistack'

export function success(message: string) {
  enqueueSnackbar(message, {
    variant: 'success',
  })
}

export function error(message: string) {
  enqueueSnackbar(message, {
    variant: 'error',
  })
}

export function warning(message: string) {
  enqueueSnackbar(message, {
    variant: 'warning',
  })
}

export function info(message: string) {
  enqueueSnackbar(message, {
    variant: 'info',
  })
}