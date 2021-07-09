import { Day } from 'react-modern-calendar-datepicker'

/**
 * Day Object To Timestamp String
 * @param dayDate The Day object to transform to a timestamp string
 * @returns A timestamp string
 */
export function dayObjectToTimestampString(dayDate: Day): string {
  const month = dayDate.month.toString(10).padStart(2, '0')
  const day = dayDate.day.toString(10).padStart(2, '0')
  return `${dayDate.year}-${month}-${day} 00:00:00`
}

/**
 * Timestamps string to day object
 * @param timestamp
 * @returns string to day object
 */
export function timestampStringToDayObject(timestamp: string): Day {
  const [year, month, dayAndTime] = timestamp.split('-')
  const day = dayAndTime.split(' ')[0]
  return {
    year: parseInt(year),
    month: parseInt(month),
    day: parseInt(day),
  }
}
