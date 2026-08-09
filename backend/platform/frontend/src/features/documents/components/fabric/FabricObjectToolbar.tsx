import {
  Button,
  Divider,
  Stack,
  TextField,
  Tooltip,
  Typography,
} from '@mui/material'

import CenterFocusStrongIcon from '@mui/icons-material/CenterFocusStrong'

import {
  useEffect,
  useState,
} from 'react'

import type {
  Canvas,
  FabricObject,
} from 'fabric'

interface Props {
  canvas: Canvas | null
}

interface ObjectState {
  x: number
  y: number
  width: number
  height: number
  angle: number
  opacity: number
}

const EMPTY_STATE: ObjectState = {
  x: 0,
  y: 0,
  width: 0,
  height: 0,
  angle: 0,
  opacity: 100,
}

export default function FabricObjectToolbar({
  canvas,
}: Props) {
  const [
    selected,
    setSelected,
  ] = useState<FabricObject | null>(
    null,
  )

  const [
    values,
    setValues,
  ] = useState<ObjectState>(
    EMPTY_STATE,
  )

  useEffect(() => {
    if (!canvas) {
      setSelected(null)
      return
    }

    function update() {
      const object =
        canvas.getActiveObject()

      if (!object) {
        setSelected(null)
        setValues(EMPTY_STATE)

        return
      }

      setSelected(object)

      const bounding =
        object.getBoundingRect()

      setValues({
        x:
          Math.round(
            object.left ?? 0,
          ),

        y:
          Math.round(
            object.top ?? 0,
          ),

        width:
          Math.round(
            bounding.width,
          ),

        height:
          Math.round(
            bounding.height,
          ),

        angle:
          Math.round(
            object.angle ?? 0,
          ),

        opacity:
          Math.round(
            (object.opacity ?? 1) *
              100,
          ),
      })
    }

    canvas.on(
      'selection:created',
      update,
    )

    canvas.on(
      'selection:updated',
      update,
    )

    canvas.on(
      'selection:cleared',
      update,
    )

    canvas.on(
      'object:moving',
      update,
    )

    canvas.on(
      'object:scaling',
      update,
    )

    canvas.on(
      'object:rotating',
      update,
    )

    canvas.on(
      'object:modified',
      update,
    )

    update()

    return () => {
      canvas.off(
        'selection:created',
        update,
      )

      canvas.off(
        'selection:updated',
        update,
      )

      canvas.off(
        'selection:cleared',
        update,
      )

      canvas.off(
        'object:moving',
        update,
      )

      canvas.off(
        'object:scaling',
        update,
      )

      canvas.off(
        'object:rotating',
        update,
      )

      canvas.off(
        'object:modified',
        update,
      )
    }
  }, [canvas])

  function modified() {
    if (
      !canvas ||
      !selected
    ) {
      return
    }

    selected.setCoords()

    canvas.requestRenderAll()

    canvas.fire(
      'object:modified',
      {
        target: selected,
      },
    )
  }

  function setPosition(
    field: 'x' | 'y',
    value: number,
  ) {
    if (
      !selected ||
      !Number.isFinite(value)
    ) {
      return
    }

    if (field === 'x') {
      selected.set({
        left: value,
      })
    } else {
      selected.set({
        top: value,
      })
    }

    setValues(
      current => ({
        ...current,
        [field]: value,
      }),
    )

    modified()
  }

  function setSize(
    field: 'width' | 'height',
    value: number,
  ) {
    if (
      !selected ||
      !Number.isFinite(value) ||
      value <= 0
    ) {
      return
    }

    const currentWidth =
      selected.getScaledWidth()

    const currentHeight =
      selected.getScaledHeight()

    if (
      field === 'width' &&
      currentWidth > 0
    ) {
      selected.scaleX =
        (selected.scaleX ?? 1) *
        (value / currentWidth)
    }

    if (
      field === 'height' &&
      currentHeight > 0
    ) {
      selected.scaleY =
        (selected.scaleY ?? 1) *
        (value / currentHeight)
    }

    setValues(
      current => ({
        ...current,
        [field]: value,
      }),
    )

    modified()
  }

  function setRotation(
    value: number,
  ) {
    if (
      !selected ||
      !Number.isFinite(value)
    ) {
      return
    }

    selected.set({
      angle: value,
    })

    setValues(
      current => ({
        ...current,
        angle: value,
      }),
    )

    modified()
  }

  function setOpacity(
    value: number,
  ) {
    if (
      !selected ||
      !Number.isFinite(value)
    ) {
      return
    }

    const safe =
      Math.max(
        0,
        Math.min(
          100,
          value,
        ),
      )

    selected.set({
      opacity:
        safe / 100,
    })

    setValues(
      current => ({
        ...current,
        opacity: safe,
      }),
    )

    modified()
  }

  function centerHorizontal() {
    if (
      !canvas ||
      !selected
    ) {
      return
    }

    canvas.centerObjectH(
      selected,
    )

    modified()
  }

  function centerVertical() {
    if (
      !canvas ||
      !selected
    ) {
      return
    }

    canvas.centerObjectV(
      selected,
    )

    modified()
  }

  if (!selected) {
    return null
  }

  return (
    <Stack
      direction="row"
      spacing={0.75}
      alignItems="center"
      sx={{
        flexWrap: 'wrap',
        rowGap: 0.75,
      }}
    >
      <Divider
        orientation="vertical"
        flexItem
      />

      <Typography
        variant="caption"
        fontWeight={700}
      >
        Position
      </Typography>

      <TextField
        size="small"
        label="X"
        type="number"
        value={values.x}
        sx={{
          width: 82,
        }}
        onChange={event =>
          setPosition(
            'x',
            Number(
              event.target.value,
            ),
          )
        }
      />

      <TextField
        size="small"
        label="Y"
        type="number"
        value={values.y}
        sx={{
          width: 82,
        }}
        onChange={event =>
          setPosition(
            'y',
            Number(
              event.target.value,
            ),
          )
        }
      />

      <TextField
        size="small"
        label="W"
        type="number"
        value={values.width}
        inputProps={{
          min: 1,
        }}
        sx={{
          width: 86,
        }}
        onChange={event =>
          setSize(
            'width',
            Number(
              event.target.value,
            ),
          )
        }
      />

      <TextField
        size="small"
        label="H"
        type="number"
        value={values.height}
        inputProps={{
          min: 1,
        }}
        sx={{
          width: 86,
        }}
        onChange={event =>
          setSize(
            'height',
            Number(
              event.target.value,
            ),
          )
        }
      />

      <TextField
        size="small"
        label="°"
        type="number"
        value={values.angle}
        sx={{
          width: 76,
        }}
        onChange={event =>
          setRotation(
            Number(
              event.target.value,
            ),
          )
        }
      />

      <TextField
        size="small"
        label="Opacity %"
        type="number"
        value={values.opacity}
        inputProps={{
          min: 0,
          max: 100,
        }}
        sx={{
          width: 105,
        }}
        onChange={event =>
          setOpacity(
            Number(
              event.target.value,
            ),
          )
        }
      />

      <Tooltip title="Center horizontally">
        <Button
          size="small"
          variant="outlined"
          startIcon={
            <CenterFocusStrongIcon />
          }
          onClick={
            centerHorizontal
          }
        >
          Center H
        </Button>
      </Tooltip>

      <Tooltip title="Center vertically">
        <Button
          size="small"
          variant="outlined"
          startIcon={
            <CenterFocusStrongIcon />
          }
          onClick={
            centerVertical
          }
        >
          Center V
        </Button>
      </Tooltip>
    </Stack>
  )
}