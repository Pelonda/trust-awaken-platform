import {
  Box,
  Button,
  Chip,
  Divider,
  Stack,
  Typography,
} from '@mui/material'

import BadgeIcon from '@mui/icons-material/Badge'
import LockIcon from '@mui/icons-material/Lock'

import type {
  Canvas,
} from 'fabric'

import {
  FabricText,
} from 'fabric'

interface Props {
  canvas: Canvas | null
}

interface VariableDefinition {
  label: string
  variable: string
  protected?: boolean
}

const VARIABLES:
  VariableDefinition[] = [
    {
      label:
        'Participant Name',
      variable:
        '{{participant.name}}',
    },

    {
      label:
        'Participant Email',
      variable:
        '{{participant.email}}',
    },

    {
      label:
        'Program Title',
      variable:
        '{{program.title}}',
    },

    {
      label:
        'Program Code',
      variable:
        '{{program.code}}',
    },

    {
      label:
        'Issue Date',
      variable:
        '{{credential.issue_date}}',
    },

    {
      label:
        'Expiry Date',
      variable:
        '{{credential.expiry_date}}',
    },

    {
      label:
        'Organization Name',
      variable:
        '{{organization.name}}',
    },

    {
      label:
        'Trainer Name',
      variable:
        '{{trainer.name}}',
    },
  ]

export default function FabricVariablesPanel({
  canvas,
}: Props) {
  function addVariable(
    definition:
      VariableDefinition,
  ) {
    if (!canvas) {
      return
    }

    const object =
      new FabricText(
        definition.variable,
        {
          left: 150,
          top: 150,

          fontSize: 28,

          fontFamily:
            'Arial',

          fill:
            '#111827',
        },
      )

    object.set({
      cornerColor:
        '#2563eb',

      cornerStrokeColor:
        '#ffffff',

      borderColor:
        '#2563eb',

      cornerSize: 12,

      transparentCorners:
        false,

      padding: 2,
    })

    /*
     * Trust AWAKEN metadata.
     *
     * These properties are added to
     * Fabric serialization below.
     */
    ;(object as any).awakenType =
      'variable'

    ;(object as any).awakenVariable =
      definition.variable

    ;(object as any).awakenProtected =
      Boolean(
        definition.protected,
      )

    canvas.add(
      object,
    )

    canvas.setActiveObject(
      object,
    )

    canvas.requestRenderAll()
  }

  function addCredentialId() {
    if (!canvas) {
      return
    }

    const object =
      new FabricText(
        '{{credential.number}}',
        {
          left: 150,
          top: 520,

          fontSize: 20,

          fontFamily:
            'Arial',

          fill:
            '#334155',
        },
      )

    object.set({
      cornerColor:
        '#7c3aed',

      cornerStrokeColor:
        '#ffffff',

      borderColor:
        '#7c3aed',

      cornerSize: 12,

      transparentCorners:
        false,

      padding: 2,
    })

    /*
     * Protected means the designer may
     * move, resize, rotate and style the
     * field, but its data source remains
     * credential.number.
     */
    ;(object as any).awakenType =
      'credential-id'

    ;(object as any).awakenVariable =
      '{{credential.number}}'

    ;(object as any).awakenProtected =
      true

    canvas.add(
      object,
    )

    canvas.setActiveObject(
      object,
    )

    canvas.requestRenderAll()
  }

  return (
    <Box>
      <Stack
        direction="row"
        alignItems="center"
        spacing={1}
        sx={{
          mb: 1,
        }}
      >
        <BadgeIcon
          fontSize="small"
        />

        <Typography
          variant="subtitle1"
          fontWeight={700}
        >
          Variables
        </Typography>
      </Stack>

      <Typography
        variant="caption"
        color="text.secondary"
      >
        Insert dynamic credential
        information.
      </Typography>

      <Stack
        spacing={1}
        sx={{
          mt: 2,
        }}
      >
        {VARIABLES.map(
          definition => (
            <Button
              key={
                definition.variable
              }
              size="small"
              variant="outlined"
              fullWidth
              sx={{
                justifyContent:
                  'flex-start',

                textTransform:
                  'none',
              }}
              onClick={() =>
                addVariable(
                  definition,
                )
              }
            >
              {definition.label}
            </Button>
          ),
        )}
      </Stack>

      <Divider
        sx={{
          my: 2,
        }}
      />

      <Stack
        direction="row"
        spacing={1}
        alignItems="center"
        sx={{
          mb: 1,
        }}
      >
        <LockIcon
          fontSize="small"
        />

        <Typography
          variant="subtitle2"
          fontWeight={700}
        >
          Protected Fields
        </Typography>
      </Stack>

      <Button
        variant="contained"
        fullWidth
        startIcon={
          <BadgeIcon />
        }
        disabled={!canvas}
        onClick={
          addCredentialId
        }
      >
        Credential ID
      </Button>

      <Chip
        size="small"
        icon={
          <LockIcon />
        }
        label="Backend controlled"
        sx={{
          mt: 1,
        }}
      />

      <Typography
        variant="caption"
        color="text.secondary"
        sx={{
          display: 'block',
          mt: 1,
        }}
      >
        The Credential ID is generated
        automatically when the credential
        is issued. The designer controls
        its position and appearance only.
      </Typography>
    </Box>
  )
}