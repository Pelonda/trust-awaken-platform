import {
  IconButton,
  List,
  ListItemButton,
  ListItemIcon,
  ListItemText,
  Stack,
  Typography,
} from '@mui/material'

import VisibilityIcon from '@mui/icons-material/Visibility'
import VisibilityOffIcon from '@mui/icons-material/VisibilityOff'
import LockIcon from '@mui/icons-material/Lock'
import LockOpenIcon from '@mui/icons-material/LockOpen'

import { useDesignerStore } from './stores/designerStore'

export default function LayersPanel() {

  const objects =
    useDesignerStore(
      (state) => state.objects,
    )

  const selectedId =
    useDesignerStore(
      (state) => state.selectedId,
    )

  const select =
    useDesignerStore(
      (state) => state.select,
    )

  const update =
    useDesignerStore(
      (state) => state.update,
    )

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

        {objects
          .slice()
          .reverse()
          .map((object) => (

            <ListItemButton
              key={object.id}
              selected={
                selectedId === object.id
              }
              onClick={() =>
                select(object.id)
              }
            >

              <ListItemText
                primary={
                  object.type
                }
                secondary={
                  object.id.substring(
                    0,
                    8,
                  )
                }
              />

              <Stack
                direction="row"
              >

                <IconButton
                  size="small"
                  onClick={(e) => {

                    e.stopPropagation()

                    update(
                      object.id,
                      {
                        visible:
                          object.visible ===
                          false,
                      },
                    )

                  }}
                >

                  {object.visible ===
                  false ? (

                    <VisibilityOffIcon
                      fontSize="small"
                    />

                  ) : (

                    <VisibilityIcon
                      fontSize="small"
                    />

                  )}

                </IconButton>

                <IconButton
                  size="small"
                  onClick={(e) => {

                    e.stopPropagation()

                    update(
                      object.id,
                      {
                        locked:
                          !object.locked,
                      },
                    )

                  }}
                >

                  {object.locked ? (

                    <LockIcon
                      fontSize="small"
                    />

                  ) : (

                    <LockOpenIcon
                      fontSize="small"
                    />

                  )}

                </IconButton>

              </Stack>

            </ListItemButton>

          ))}

      </List>

    </>

  )

}