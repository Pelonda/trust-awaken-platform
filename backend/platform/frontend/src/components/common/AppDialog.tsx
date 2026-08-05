import {
  Button,
  Dialog,
  DialogActions,
  DialogContent,
  DialogTitle,
} from '@mui/material'

import { ReactNode } from 'react'

interface Props {
  open: boolean
  title: string
  children: ReactNode
  onClose: () => void
  onSave: () => void
  saving?: boolean
}

export default function AppDialog({
  open,
  title,
  children,
  onClose,
  onSave,
  saving = false,
}: Props) {
  return (
    <Dialog
      open={open}
      onClose={onClose}
      maxWidth="md"
      fullWidth
    >
      <DialogTitle>{title}</DialogTitle>

      <DialogContent dividers>
        {children}
      </DialogContent>

      <DialogActions>

        <Button
          onClick={onClose}
        >
          Cancel
        </Button>

        <Button
          variant="contained"
          onClick={onSave}
          disabled={saving}
        >
          {saving ? 'Saving...' : 'Save'}
        </Button>

      </DialogActions>

    </Dialog>
  )
}