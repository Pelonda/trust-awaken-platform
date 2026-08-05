import {
  Button,
  Dialog,
  DialogActions,
  DialogContent,
  DialogTitle,
} from '@mui/material'

import { useState } from 'react'
import { useQueryClient } from '@tanstack/react-query'

import * as Toast from '../../components/common/AppToast'

import AttendanceForm from './AttendanceForm'
import { useCreateAttendance } from './mutations'

interface Props {
  open: boolean
  onClose: () => void
}

export default function AttendanceDialog({
  open,
  onClose,
}: Props) {

  const queryClient = useQueryClient()

  const mutation = useCreateAttendance()

  const [form, setForm] = useState({
    participant_id: '',
    session_id: '',
    status: 'present',
  })

  function reset() {
    setForm({
      participant_id: '',
      session_id: '',
      status: 'present',
    })
  }

  function save() {

    mutation.mutate(
      form,
      {
        onSuccess: async () => {

          Toast.success(
            'Attendance recorded.'
          )

          await queryClient.invalidateQueries({
            queryKey: ['attendance'],
          })

          reset()

          onClose()

        },

        onError: () => {

          Toast.error(
            'Unable to save attendance.'
          )

        },

      }
    )

  }

  return (

    <Dialog
      open={open}
      onClose={onClose}
      fullWidth
      maxWidth="sm"
    >

      <DialogTitle>
        Record Attendance
      </DialogTitle>

      <DialogContent dividers>

        <AttendanceForm
          value={form}
          onChange={setForm}
        />

      </DialogContent>

      <DialogActions>

        <Button onClick={onClose}>
          Cancel
        </Button>

        <Button
          variant="contained"
          onClick={save}
          disabled={mutation.isPending}
        >
          {mutation.isPending
            ? 'Saving...'
            : 'Save'}
        </Button>

      </DialogActions>

    </Dialog>

  )

}