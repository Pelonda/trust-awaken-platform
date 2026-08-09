import {
  Box,
  Divider,
  IconButton,
  List,
  ListItemButton,
  ListItemText,
  Stack,
  Tooltip,
  Typography,
} from '@mui/material'

import VisibilityIcon from '@mui/icons-material/Visibility'
import VisibilityOffIcon from '@mui/icons-material/VisibilityOff'
import LockIcon from '@mui/icons-material/Lock'
import LockOpenIcon from '@mui/icons-material/LockOpen'
import ContentCopyIcon from '@mui/icons-material/ContentCopy'
import DeleteIcon from '@mui/icons-material/Delete'
import ArrowUpwardIcon from '@mui/icons-material/ArrowUpward'
import ArrowDownwardIcon from '@mui/icons-material/ArrowDownward'

import {
  useEffect,
  useState,
} from 'react'

import {
  FabricImage,
  FabricObject,
  FabricText,
  type Canvas,
} from 'fabric'

import {
  isProtectedObject,
} from './FabricProtectedFields'

interface Props {
  canvas: Canvas | null
}

interface LayerItem {
  object: FabricObject
  index: number
  label: string
}

function getLabel(
  object: FabricObject,
  index: number,
): string {
  if (
    object instanceof
    FabricText
  ) {
    const text =
      object.text?.trim()

    if (text) {
      return text.length > 24
        ? `${text.slice(0, 24)}…`
        : text
    }

    return 'Text'
  }

  if (
    object instanceof
    FabricImage
  ) {
    return 'Image'
  }

  if (
    object.type === 'rect'
  ) {
    return 'Rectangle'
  }

  return (
    object.type ??
    `Object ${index + 1}`
  )
}

export default function FabricLayersPanel({
  canvas,
}: Props) {
  const [
    layers,
    setLayers,
  ] = useState<LayerItem[]>([])

  const [
    selected,
    setSelected,
  ] = useState<FabricObject | null>(
    null,
  )

  function refresh() {
    if (!canvas) {
      setLayers([])
      setSelected(null)

      return
    }

    const objects =
      canvas.getObjects()

    /*
     * Reverse the display so the
     * top-most canvas object appears
     * first in the Layers panel.
     */
    const items =
      objects
        .map(
          (
            object,
            index,
          ): LayerItem => ({
            object,
            index,
            label:
              getLabel(
                object,
                index,
              ),
          }),
        )
        .reverse()

    setLayers(items)

    const active =
      canvas.getActiveObject()

    setSelected(
      active ?? null,
    )
  }

  useEffect(() => {
    if (!canvas) {
      setLayers([])
      setSelected(null)

      return
    }

    const events = [
      'object:added',
      'object:removed',
      'object:modified',
      'selection:created',
      'selection:updated',
      'selection:cleared',
    ] as const

    events.forEach(
      event => {
        canvas.on(
          event,
          refresh,
        )
      },
    )

    refresh()

    return () => {
      events.forEach(
        event => {
          canvas.off(
            event,
            refresh,
          )
        },
      )
    }
  }, [canvas])

  function selectObject(
    object: FabricObject,
  ) {
    if (!canvas) {
      return
    }

    canvas.setActiveObject(
      object,
    )

    canvas.requestRenderAll()

    refresh()
  }

  function toggleVisibility(
    object: FabricObject,
  ) {
    if (!canvas) {
      return
    }

    object.set(
      'visible',
      object.visible === false,
    )

    if (
      object.visible ===
        false &&
      canvas.getActiveObject() ===
        object
    ) {
      canvas.discardActiveObject()
    }

    object.setCoords()

    canvas.requestRenderAll()

    refresh()
  }

  function toggleLock(
    object: FabricObject,
  ) {
    if (!canvas) {
      return
    }

    const locked =
      Boolean(
        object.lockMovementX &&
        object.lockMovementY,
      )

    const next =
      !locked

    object.set({
      lockMovementX:
        next,

      lockMovementY:
        next,

      lockScalingX:
        next,

      lockScalingY:
        next,

      lockRotation:
        next,

      hasControls:
        !next,
    })

    object.setCoords()

    canvas.requestRenderAll()

    refresh()
  }

  async function duplicateObject(
    object: FabricObject,
  ) {

    if (
  isProtectedObject(
    object,
  )
) {
  return
}

    if (!canvas) {
      return
    }

    const clone =
      await object.clone()

    clone.set({
      left:
        (object.left ?? 0) +
        20,

      top:
        (object.top ?? 0) +
        20,
    })

    clone.setCoords()

    canvas.add(
      clone,
    )

    canvas.setActiveObject(
      clone,
    )

    canvas.requestRenderAll()

    refresh()
  }

  function deleteObject(
  object: FabricObject,
) {
  if (!canvas) {
    return
  }

  canvas.remove(
    object,
  )

  canvas.discardActiveObject()

  canvas.requestRenderAll()

  refresh()
}

  function bringForward(
    object: FabricObject,
  ) {
    if (!canvas) {
      return
    }

    canvas.bringObjectForward(
      object,
    )

    canvas.requestRenderAll()

    refresh()
  }

  function sendBackward(
    object: FabricObject,
  ) {
    if (!canvas) {
      return
    }

    canvas.sendObjectBackwards(
      object,
    )

    canvas.requestRenderAll()

    refresh()
  }

  return (
    <Box
      sx={{
        width: 280,
        height: '100%',
        bgcolor: '#ffffff',
        borderLeft:
          '1px solid #e2e8f0',
        overflow: 'auto',
      }}
    >
      <Box sx={{ p: 2 }}>
        <Typography
          variant="h6"
          fontWeight={700}
        >
          Layers
        </Typography>

        <Typography
          variant="caption"
          color="text.secondary"
        >
          {layers.length}{' '}
          {layers.length === 1
            ? 'object'
            : 'objects'}
        </Typography>
      </Box>

      <Divider />

      {layers.length === 0 ? (
        <Typography
          variant="body2"
          color="text.secondary"
          sx={{
            p: 2,
          }}
        >
          No objects on this page.
        </Typography>
      ) : (
        <List
          disablePadding
          dense
        >
          {layers.map(
            ({
              object,
              index,
              label,
            }) => {
              const locked =
                Boolean(
                  object.lockMovementX &&
                  object.lockMovementY,
                )

              const visible =
                object.visible !==
                false

              return (
                <ListItemButton
                  key={`${object.type}-${index}`}
                  selected={
                    selected ===
                    object
                  }
                  onClick={() =>
                    selectObject(
                      object,
                    )
                  }
                  sx={{
                    px: 1.5,
                    py: 1,
                  }}
                >
                  <ListItemText
                    primary={
                      label
                    }
                    secondary={
                      object.type
                    }
                    primaryTypographyProps={{
                      noWrap: true,
                    }}
                    sx={{
                      minWidth: 0,
                      mr: 1,
                    }}
                  />

                  <Stack
                    direction="row"
                    spacing={0}
                    onClick={event =>
                      event.stopPropagation()
                    }
                  >
                    <Tooltip
                      title={
                        visible
                          ? 'Hide'
                          : 'Show'
                      }
                    >
                      <IconButton
                        size="small"
                        onClick={() =>
                          toggleVisibility(
                            object,
                          )
                        }
                      >
                        {visible ? (
                          <VisibilityIcon
                            fontSize="small"
                          />
                        ) : (
                          <VisibilityOffIcon
                            fontSize="small"
                          />
                        )}
                      </IconButton>
                    </Tooltip>

                    <Tooltip
                      title={
                        locked
                          ? 'Unlock'
                          : 'Lock'
                      }
                    >
                      <IconButton
                        size="small"
                        onClick={() =>
                          toggleLock(
                            object,
                          )
                        }
                      >
                        {locked ? (
                          <LockIcon
                            fontSize="small"
                          />
                        ) : (
                          <LockOpenIcon
                            fontSize="small"
                          />
                        )}
                      </IconButton>
                    </Tooltip>

                    <Tooltip title="Bring forward">
                      <IconButton
                        size="small"
                        onClick={() =>
                          bringForward(
                            object,
                          )
                        }
                      >
                        <ArrowUpwardIcon
                          fontSize="small"
                        />
                      </IconButton>
                    </Tooltip>

                    <Tooltip title="Send backward">
                      <IconButton
                        size="small"
                        onClick={() =>
                          sendBackward(
                            object,
                          )
                        }
                      >
                        <ArrowDownwardIcon
                          fontSize="small"
                        />
                      </IconButton>
                    </Tooltip>

                    <Tooltip title="Duplicate">
                      <IconButton
                        size="small"
                        onClick={() =>
                          void duplicateObject(
                            object,
                          )
                        }
                      >
                        <ContentCopyIcon
                          fontSize="small"
                        />
                      </IconButton>
                    </Tooltip>

                    <Tooltip title="Delete">
                      <IconButton
                        size="small"
                        color="error"
                        onClick={() =>
                          deleteObject(
                            object,
                          )
                        }
                      >
                        <DeleteIcon
                          fontSize="small"
                        />
                      </IconButton>
                    </Tooltip>
                  </Stack>
                </ListItemButton>
              )
            },
          )}
        </List>
      )}
    </Box>
  )
}