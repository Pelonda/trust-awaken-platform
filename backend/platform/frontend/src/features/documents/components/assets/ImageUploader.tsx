import {
  Button,
} from '@mui/material'

import UploadIcon from '@mui/icons-material/Upload'

interface Props {
  onUpload: (file: File) => void
}

export default function ImageUploader({
  onUpload,
}: Props) {

  return (

    <Button
      component="label"
      variant="outlined"
      fullWidth
      startIcon={<UploadIcon />}
    >

      Upload Image

      <input
        hidden
        type="file"
        accept="image/*"
        onChange={(e) => {

          const file =
            e.target.files?.[0]

          if (file) {

            onUpload(file)

          }

        }}
      />

    </Button>

  )

}