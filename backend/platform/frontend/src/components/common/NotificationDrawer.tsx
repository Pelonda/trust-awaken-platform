import {
  Divider,
  Drawer,
  List,
  ListItem,
  ListItemText,
  Typography,
} from '@mui/material'

interface Props {
  open: boolean
  onClose: () => void
}

const notifications = [
  'Credential issued to John Doe',
  'Participant registered',
  'Attendance recorded',
  'Program created',
  'Session completed',
]

export default function NotificationDrawer({
  open,
  onClose,
}: Props) {
  return (
    <Drawer
      anchor="right"
      open={open}
      onClose={onClose}
    >
      <Typography
        variant="h6"
        fontWeight={700}
        sx={{ p: 3 }}
      >
        Notifications
      </Typography>

      <Divider />

      <List sx={{ width: 360 }}>

        {notifications.map((item) => (

          <ListItem key={item}>
            <ListItemText
              primary={item}
            />
          </ListItem>

        ))}

      </List>

    </Drawer>
  )
}