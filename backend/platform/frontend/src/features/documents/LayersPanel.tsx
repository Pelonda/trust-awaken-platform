import {
  List,
  ListItemButton,
  ListItemText,
  Typography,
} from '@mui/material'

export default function LayersPanel() {
  return (
    <>
      <Typography
        variant="h6"
        fontWeight={700}
        sx={{ mb: 2 }}
      >
        Layers
      </Typography>

      <List dense>

        <ListItemButton>
          <ListItemText primary="Background" />
        </ListItemButton>

        <ListItemButton>
          <ListItemText primary="Logo" />
        </ListItemButton>

        <ListItemButton>
          <ListItemText primary="Recipient Name" />
        </ListItemButton>

        <ListItemButton>
          <ListItemText primary="QR Code" />
        </ListItemButton>

      </List>
    </>
  )
}