import {
  Stack,
  Switch,
  TextField,
  Typography,
  FormControlLabel,
} from '@mui/material'

import { useDesignerStore }
from './stores/designerStore'

export default function PropertiesPanel(){

  const selected=
    useDesignerStore(
      state=>state.selected(),
    )

  const update=
    useDesignerStore(
      state=>state.update,
    )

  if(!selected){

    return(

      <Typography>

        Select object

      </Typography>

    )

  }

  return(

    <Stack spacing={2}>

      <Typography
        variant="h6"
      >

        Inspector

      </Typography>

      <TextField
        label="X"
        type="number"
        value={selected.x}
        onChange={e=>

          update(

            selected.id,

            {

              x:Number(
                e.target.value,
              ),

            },

          )

        }
      />

      <TextField
        label="Y"
        type="number"
        value={selected.y}
        onChange={e=>

          update(

            selected.id,

            {

              y:Number(
                e.target.value,
              ),

            },

          )

        }
      />

      <TextField
        label="Width"
        type="number"
        value={selected.width}
        onChange={e=>

          update(

            selected.id,

            {

              width:Number(
                e.target.value,
              ),

            },

          )

        }
      />

      <TextField
        label="Height"
        type="number"
        value={selected.height}
        onChange={e=>

          update(

            selected.id,

            {

              height:Number(
                e.target.value,
              ),

            },

          )

        }
      />

      <TextField
        label="Rotation"
        type="number"
        value={selected.rotation}
        onChange={e=>

          update(

            selected.id,

            {

              rotation:Number(
                e.target.value,
              ),

            },

          )

        }
      />

      <TextField
        label="Opacity"
        type="number"
        inputProps={{
          step:0.1,
          min:0,
          max:1,
        }}
        value={selected.opacity}
        onChange={e=>

          update(

            selected.id,

            {

              opacity:Number(
                e.target.value,
              ),

            },

          )

        }
      />

      <FormControlLabel

        control={

          <Switch

            checked={
              selected.visible
            }

            onChange={e=>

              update(

                selected.id,

                {

                  visible:
                  e.target.checked,

                },

              )

            }

          />

        }

        label="Visible"

      />

      <FormControlLabel

        control={

          <Switch

            checked={
              selected.locked
            }

            onChange={e=>

              update(

                selected.id,

                {

                  locked:
                  e.target.checked,

                },

              )

            }

          />

        }

        label="Locked"

      />

    </Stack>

  )

}