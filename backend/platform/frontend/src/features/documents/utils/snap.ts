export const GRID_SIZE = 20

export function snap(value: number): number {
  return Math.round(value / GRID_SIZE) * GRID_SIZE
}