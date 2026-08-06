import {
  List,
  ListItemButton,
  ListItemText,
  Typography,
} from '@mui/material'

const assets = [
  'Logo',
  'Image',
  'QR Code',
  'Barcode',
  'Signature',
  'Text',
  'Rectangle',
  'Circle',
  'Line',
]

export default function AssetLibrary() {
  return (
    <>
      <Typography
        variant="h6"
        fontWeight={700}
        sx={{ mb: 2 }}
      >
        Assets
      </Typography>

      <List dense>

        {assets.map((asset) => (

          <ListItemButton
            key={asset}
          >
            <ListItemText
              primary={asset}
            />
          </ListItemButton>

        ))}

      </List>
    </>
  )
}